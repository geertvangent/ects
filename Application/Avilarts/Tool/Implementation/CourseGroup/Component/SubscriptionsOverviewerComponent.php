<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\CourseGroup\Component;

use Ehb\Application\Avilarts\CourseSettingsController;
use Ehb\Application\Avilarts\Tool\Implementation\CourseGroup\Manager;
use Ehb\Application\Avilarts\Tool\Implementation\CourseGroup\Storage\DataClass\CourseGroup;
use Ehb\Application\Avilarts\Tool\Implementation\CourseGroup\Storage\DataManager;
use Ehb\Application\Avilarts\Tool\Implementation\CourseGroup\Table\Overview\CourseUser\CourseUsersTable;
use Ehb\Application\Avilarts\Tool\Implementation\CourseGroup\Table\Overview\GroupUser\CourseGroupUserTable;
use Chamilo\Core\User\Storage\DataClass\User;
use Chamilo\Libraries\Format\Structure\ActionBar\Button;
use Chamilo\Libraries\Format\Structure\ActionBar\Renderer\ButtonToolBarRenderer;
use Chamilo\Libraries\Format\Structure\ActionBar\ButtonToolBar;
use Chamilo\Libraries\Format\Structure\ActionBar\ButtonGroup;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
use Chamilo\Libraries\Format\Structure\ToolbarItem;
use Chamilo\Libraries\Format\Table\Interfaces\TableSupport;
use Chamilo\Libraries\Format\Tabs\DynamicVisualTab;
use Chamilo\Libraries\Format\Tabs\DynamicVisualTabsRenderer;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Storage\Query\Condition\AndCondition;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\Condition\InequalityCondition;
use Chamilo\Libraries\Storage\Query\Condition\OrCondition;
use Chamilo\Libraries\Storage\Query\Condition\PatternMatchCondition;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Chamilo\Libraries\Utilities\Utilities;
use Chamilo\Libraries\Architecture\Exceptions\NotAllowedException;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;

class SubscriptionsOverviewerComponent extends Manager implements TableSupport
{
    const TAB_USERS = 1;
    const TAB_COURSE_GROUPS = 2;
    const PLATFORM_GROUP_ROOT_ID = 0;

    /**
     *
     * @var ButtonToolBarRenderer
     */
    private $buttonToolbarRenderer;

    private $current_tab;

    /**
     * Temporary variable for condition building
     * 
     * @var int
     */
    private $table_course_group_id;

    public function run()
    {
        if (! $this->is_allowed(\Ehb\Application\Avilarts\Rights\Rights :: EDIT_RIGHT))
        {
            throw new NotAllowedException();
        }
        
        $this->current_tab = self :: TAB_COURSE_GROUPS;
        if (Request :: get(self :: PARAM_TAB))
        {
            $this->current_tab = Request :: get(self :: PARAM_TAB);
        }
        
        $html = array();
        
        $html[] = $this->render_header();
        
        $course_settings_controller = CourseSettingsController :: get_instance();
        
        if ($course_settings_controller->get_course_setting(
            $this->get_course_id(), 
            \Ehb\Application\Avilarts\CourseSettingsConnector :: ALLOW_INTRODUCTION_TEXT))
        {
            $html[] = $this->display_introduction_text($this->get_introduction_text());
        }
        
        $this->buttonToolbarRenderer = $this->getButtonToolbarRenderer();
        $html[] = $this->buttonToolbarRenderer->as_html();
        $html[] = $this->get_tabs();
        $html[] = $this->render_footer();
        
        return implode(PHP_EOL, $html);
    }
    
    // **************************************************************************
    // TABS FUNCTIONS
    // **************************************************************************
    /**
     * Creates the tab structure.
     * 
     * @return String HTML of the tab(s)
     */
    private function get_tabs()
    {
        $tabs = new DynamicVisualTabsRenderer('weblcms_course_user_browser');
        
        // all tab
        $link = $this->get_url(array(self :: PARAM_TAB => self :: TAB_USERS));
        $tab_name = Translation :: get('User');
        $tabs->add_tab(
            new DynamicVisualTab(
                self :: TAB_USERS, 
                $tab_name, 
                Theme :: getInstance()->getCommonImagePath('Place/Users'), 
                $link, 
                $this->current_tab == self :: TAB_USERS));
        
        // users tab
        $link = $this->get_url(array(self :: PARAM_TAB => self :: TAB_COURSE_GROUPS));
        $tab_name = Translation :: get('CourseGroup');
        $tabs->add_tab(
            new DynamicVisualTab(
                self :: TAB_COURSE_GROUPS, 
                $tab_name, 
                Theme :: getInstance()->getCommonImagePath('Place/User'), 
                $link, 
                $this->current_tab == self :: TAB_COURSE_GROUPS));
        
        $tabs->set_content($this->get_tabs_content());
        
        return $tabs->render();
    }

    /**
     * Creates the content of the selected tab.
     * 
     * @return String HTML of the content
     */
    private function get_tabs_content()
    {
        switch ($this->current_tab)
        {
            case self :: TAB_USERS :
                return $this->get_users_tab();
                break;
            case self :: TAB_COURSE_GROUPS :
                return $this->get_course_groups_tab();
                break;
        }
    }

    private function get_users_tab()
    {
        $table = new CourseUsersTable($this);
        return $table->as_html();
    }

    private function get_course_groups_tab()
    {
        $course_groups = DataManager :: retrieves(
            CourseGroup :: class_name(), 
            new DataClassRetrievesParameters($this->get_condition()));
        
        $html_tables = '';
        while ($course_group = $course_groups->next_result())
        {
            $this->table_course_group_id = $course_group->get_id();
            
            $table = new CourseGroupUserTable($this);
            $html_tables = $html_tables . '<h4>' . $course_group->get_name() . '</h4>' . $table->as_html();
        }
        return $html_tables;
    }

    public function get_table_course_group_id()
    {
        return $this->table_course_group_id;
    }

    public function get_group()
    {
        $group = Request :: get(\Ehb\Application\Avilarts\Manager :: PARAM_GROUP);
        if (! $group)
        {
            return self :: PLATFORM_GROUP_ROOT_ID;
        }
        return $group;
    }

    public function getButtonToolbarRenderer()
    {
        if (! isset($this->buttonToolbarRenderer))
        {
            
            $parameters = array();
            $buttonToolbar = new ButtonToolBar($this->get_url($parameters));
            $commonActions = new ButtonGroup();
            
            $param_export_subscriptions_overview[\Ehb\Application\Avilarts\Tool\Manager :: PARAM_ACTION] = self :: ACTION_EXPORT_SUBSCRIPTIONS_OVERVIEW;
            $param_export_subscriptions_overview[self :: PARAM_TAB] = $this->current_tab;
            
            if ($this->is_allowed(\Ehb\Application\Avilarts\Rights\Rights :: VIEW_RIGHT))
            {
                $commonActions->addButton(
                    new Button(
                        Translation :: get('Export', null, Utilities :: COMMON_LIBRARIES), 
                        Theme :: getInstance()->getCommonImagePath('Action/Backup'), 
                        $this->get_url($param_export_subscriptions_overview), 
                        ToolbarItem :: DISPLAY_ICON_AND_LABEL));
            }
            
            $buttonToolbar->addButtonGroup($commonActions);
            
            $this->buttonToolbarRenderer = new ButtonToolBarRenderer($buttonToolbar);
        }
        
        return $this->buttonToolbarRenderer;
    }

    public function get_condition()
    {
        $conditions = array();
        $search_condition = $this->get_search_condition();
        if ($search_condition)
        {
            $conditions[] = $search_condition;
        }
        
        if ($this->current_tab == self :: TAB_COURSE_GROUPS)
        {
            $conditions[] = new EqualityCondition(
                new PropertyConditionVariable(CourseGroup :: class_name(), CourseGroup :: PROPERTY_COURSE_CODE), 
                new StaticConditionVariable($this->get_course_id()));
            $conditions[] = new InequalityCondition(
                new PropertyConditionVariable(CourseGroup :: class_name(), CourseGroup :: PROPERTY_PARENT_ID), 
                InequalityCondition :: GREATER_THAN, 
                new StaticConditionVariable(0));
        }
        if ($conditions)
        {
            return new AndCondition($conditions);
        }
        return null;
    }

    public function get_search_condition()
    {
        $query = $this->buttonToolbarRenderer->getSearchForm()->getQuery();
        if (isset($query) && $query != '')
        {
            $conditions = array();
            switch ($this->current_tab)
            {
                case self :: TAB_USERS :
                    $conditions[] = new PatternMatchCondition(
                        new PropertyConditionVariable(User :: class_name(), User :: PROPERTY_OFFICIAL_CODE), 
                        '*' . $query . '*');
                    $conditions[] = new PatternMatchCondition(
                        new PropertyConditionVariable(User :: class_name(), User :: PROPERTY_LASTNAME), 
                        '*' . $query . '*');
                    $conditions[] = new PatternMatchCondition(
                        new PropertyConditionVariable(User :: class_name(), User :: PROPERTY_FIRSTNAME), 
                        '*' . $query . '*');
                    $conditions[] = new PatternMatchCondition(
                        new PropertyConditionVariable(User :: class_name(), User :: PROPERTY_USERNAME), 
                        '*' . $query . '*');
                    $conditions[] = new PatternMatchCondition(
                        new PropertyConditionVariable(User :: class_name(), User :: PROPERTY_EMAIL), 
                        '*' . $query . '*');
                    break;
                case self :: TAB_COURSE_GROUPS :
                    $conditions[] = new PatternMatchCondition(
                        new PropertyConditionVariable(CourseGroup :: class_name(), CourseGroup :: PROPERTY_NAME), 
                        '*' . $query . '*', 
                        \Ehb\Application\Avilarts\Storage\DataManager :: get_instance()->get_alias(
                            CourseGroup :: get_table_name()), 
                        true);
                    $conditions[] = new PatternMatchCondition(
                        new PropertyConditionVariable(CourseGroup :: class_name(), CourseGroup :: PROPERTY_DESCRIPTION), 
                        '*' . $query . '*', 
                        \Ehb\Application\Avilarts\Storage\DataManager :: get_instance()->get_alias(
                            CourseGroup :: get_table_name()), 
                        true);
                    break;
            }
            return new OrCondition($conditions);
        }
        return null;
    }

    public function get_additional_parameters()
    {
        return array(self :: PARAM_TAB, \Ehb\Application\Avilarts\Manager :: PARAM_GROUP);
    }

    public function add_additional_breadcrumbs(BreadcrumbTrail $breadcrumbtrail)
    {
        $breadcrumbtrail->add_help('weblcms_course_groups_overview');
    }

    /**
     * Returns the condition
     * 
     * @param string $table_class_name
     *
     * @return \libraries\storage\Condition
     */
    public function get_table_condition($table_class_name)
    {
        return $this->get_condition();
    }
}
