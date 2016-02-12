<?php
namespace Ehb\Application\Avilarts\Tool\Action\Component;

use Ehb\Application\Avilarts\CourseSettingsConnector;
use Ehb\Application\Avilarts\CourseSettingsController;
use Ehb\Application\Avilarts\Menu\PublicationCategoriesTree;
use Ehb\Application\Avilarts\Renderer\PublicationList\ContentObjectPublicationListRenderer;
use Ehb\Application\Avilarts\Storage\DataClass\ContentObjectPublication;
use Ehb\Application\Avilarts\Storage\DataClass\ContentObjectPublicationCategory;
use Ehb\Application\Avilarts\Storage\DataClass\CourseSection;
use Ehb\Application\Avilarts\Tool\Action\Manager;
use Chamilo\Core\Repository\Storage\DataClass\ContentObject;
use Chamilo\Libraries\Architecture\Application\Application;
use Chamilo\Libraries\Architecture\Interfaces\Categorizable;
use Chamilo\Libraries\File\Path;
use Chamilo\Libraries\Format\Display;
use Chamilo\Libraries\Format\NotificationMessage;
use Chamilo\Libraries\Format\Structure\ActionBar\Button;
use Chamilo\Libraries\Format\Structure\ActionBar\Renderer\ButtonToolBarRenderer;
use Chamilo\Libraries\Format\Structure\ActionBar\ButtonToolBar;
use Chamilo\Libraries\Format\Structure\ActionBar\ButtonGroup;
use Chamilo\Libraries\Format\Structure\ToolbarItem;
use Chamilo\Libraries\Format\Table\FormAction\TableFormAction;
use Chamilo\Libraries\Format\Table\FormAction\TableFormActions;
use Chamilo\Libraries\Format\Tabs\DynamicVisualTab;
use Chamilo\Libraries\Format\Tabs\DynamicVisualTabsRenderer;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Format\Utilities\ResourceManager;
use Chamilo\Libraries\Platform\Configuration\PlatformSetting;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Platform\Session\Session;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Storage\Query\Condition\AndCondition;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\Condition\InCondition;
use Chamilo\Libraries\Storage\Query\Condition\InequalityCondition;
use Chamilo\Libraries\Storage\Query\Condition\OrCondition;
use Chamilo\Libraries\Storage\Query\Condition\PatternMatchCondition;
use Chamilo\Libraries\Storage\Query\OrderBy;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Chamilo\Libraries\Utilities\StringUtilities;
use Chamilo\Libraries\Utilities\Utilities;
use Chamilo\Libraries\Architecture\Exceptions\NotAllowedException;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;

/**
 * $Id: viewer.class.php 200 2009-11-13 12:30:04Z kariboe $
 * 
 * @package repository.lib.complex_display.assessment.component
 */
class BrowserComponent extends Manager
{

    /**
     *
     * @var ButtonToolBarRenderer
     */
    private $buttonToolbarRenderer;

    private $introduction_text;

    private $publication_category_tree;

    private $publications;

    private $is_course_admin;

    public function run()
    {
        // set if we are browsing as course admin, used for displaying the
        // additional tabs and actions
        $this->is_course_admin = $this->get_course()->is_course_admin($this->get_user());
        
        if (! $this->is_allowed(\Ehb\Application\Avilarts\Rights\Rights :: VIEW_RIGHT))
        {
            throw new NotAllowedException();
        }
        
        $this->introduction_text = $this->get_parent()->get_introduction_text();
        $this->buttonToolbarRenderer = $this->getButtonToolbarRenderer();
        
        $this->publication_category_tree = new PublicationCategoriesTree($this);
        
        $publication_renderer = ContentObjectPublicationListRenderer :: factory(
            $this->get_parent()->get_browser_type(), 
            $this);
        
        $actions = new TableFormActions(__NAMESPACE__, self :: TABLE_IDENTIFIER);
        
        $actions->add_form_action(
            new TableFormAction(
                array(
                    \Ehb\Application\Avilarts\Tool\Manager :: PARAM_ACTION => \Ehb\Application\Avilarts\Tool\Manager :: ACTION_DELETE), 
                Translation :: get('RemoveSelected', null, Utilities :: COMMON_LIBRARIES)));
        
        $actions->add_form_action(
            new TableFormAction(
                array(
                    \Ehb\Application\Avilarts\Tool\Manager :: PARAM_ACTION => \Ehb\Application\Avilarts\Tool\Manager :: ACTION_TOGGLE_VISIBILITY), 
                Translation :: get('ToggleVisibility'), 
                false));
        
        if ($this->is_allowed(\Ehb\Application\Avilarts\Rights\Rights :: EDIT_RIGHT) &&
             $this->get_parent() instanceof Categorizable)
        {
            $actions->add_form_action(
                new TableFormAction(
                    array(
                        \Ehb\Application\Avilarts\Tool\Manager :: PARAM_ACTION => \Ehb\Application\Avilarts\Tool\Manager :: ACTION_MOVE_TO_CATEGORY), 
                    Translation :: get('MoveSelected', null, Utilities :: COMMON_LIBRARIES), 
                    false));
        }
        
        $publication_renderer->set_actions($actions);
        if ($this->get_parent()->get_browser_type() == ContentObjectPublicationListRenderer :: TYPE_GALLERY ||
             $this->get_parent()->get_browser_type() == ContentObjectPublicationListRenderer :: TYPE_SLIDESHOW)
        {
            $messages = Session :: retrieve(Application :: PARAM_MESSAGES);
            $messages[Application :: PARAM_MESSAGE_TYPE][] = NotificationMessage :: TYPE_WARNING;
            $messages[Application :: PARAM_MESSAGE][] = Translation :: get('BrowserWarningPreview');
            
            Session :: register(Application :: PARAM_MESSAGES, $messages);
        }
        
        $html = array();
        
        $html[] = $this->render_header();
        
        $content = array();
        
        $course_settings_controller = CourseSettingsController :: get_instance();
        
        if ($course_settings_controller->get_course_setting(
            $this->get_course_id(), 
            \Ehb\Application\Avilarts\CourseSettingsConnector :: ALLOW_INTRODUCTION_TEXT))
        {
            $content[] = $this->get_parent()->display_introduction_text($this->introduction_text);
        }
        
        $content[] = $this->buttonToolbarRenderer->as_html();
        $content[] = '<div id="action_bar_browser" style="width:100%;">';
        
        if ($this->get_parent() instanceof Categorizable)
        {
            $content[] = '<div class="tree_menu_on_top" style="max-height:150px; overflow: auto;">';
            $content[] = '<div id="tree_menu_hide_container" class="tree_menu_hide_container" style="float: right;' .
                 'overflow: auto; ">';
            $content[] = '<a id="tree_menu_action_hide" class="tree_menu_hide" href="#">' . Translation :: get(
                'ShowAll') . '</a>';
            $content[] = '</div>';
            $content[] = '<div id=tree style="width:90%;overflow: auto;">';
            $content[] = $this->publication_category_tree->render_as_tree();
            $content[] = '</div>';
            $content[] = ResourceManager :: get_instance()->get_resource_html(
                Path :: getInstance()->getJavascriptPath('Ehb\Application\Avilarts', true) . 'TreeMenu.js');
            $content[] = '</div>';
            
            $cat_id = intval(Request :: get(\Ehb\Application\Avilarts\Manager :: PARAM_CATEGORY));
            
            if (! $cat_id || $cat_id == 0)
            {
                $cat_name = Translation :: get('Root');
            }
            else
            {
                $category = $this->retrieve_category($cat_id);
                if ($category)
                {
                    $cat_name = $category->get_name();
                }
                else
                {
                    $cat_name = Translation :: get('Root');
                }
            }
            $content[] = '<div style="color: #797268; font-weight: bold;">' . Translation :: get('CurrentCategory') .
                 ': ' . $cat_name . '</div><br />';
        }
        
        $type = $this->get_publication_type();
        
        $tabs = new DynamicVisualTabsRenderer('tool_browser');
        
        if ($this->is_course_admin)
        {
            $tabs->add_tab(
                new DynamicVisualTab(
                    \Ehb\Application\Avilarts\Tool\Manager :: PUBLICATION_TYPE_ALL, 
                    Translation :: get('AllPublications'), 
                    Theme :: getInstance()->getCommonImagePath('Treemenu/SharedObjects'), 
                    $this->get_url(
                        array(
                            \Ehb\Application\Avilarts\Tool\Manager :: PARAM_BROWSE_PUBLICATION_TYPE => \Ehb\Application\Avilarts\Tool\Manager :: PUBLICATION_TYPE_ALL)), 
                    $type == \Ehb\Application\Avilarts\Tool\Manager :: PUBLICATION_TYPE_ALL));
        }
        
        $tabs->add_tab(
            new DynamicVisualTab(
                \Ehb\Application\Avilarts\Tool\Manager :: PUBLICATION_TYPE_FROM_ME, 
                Translation :: get('PublishedForMe'), 
                Theme :: getInstance()->getCommonImagePath('Treemenu/SharedObjects'), 
                $this->get_url(
                    array(
                        \Ehb\Application\Avilarts\Tool\Manager :: PARAM_BROWSE_PUBLICATION_TYPE => \Ehb\Application\Avilarts\Tool\Manager :: PUBLICATION_TYPE_FOR_ME)), 
                $type == \Ehb\Application\Avilarts\Tool\Manager :: PUBLICATION_TYPE_FOR_ME));
        
        $tabs->add_tab(
            new DynamicVisualTab(
                \Ehb\Application\Avilarts\Tool\Manager :: PUBLICATION_TYPE_FROM_ME, 
                Translation :: get('MyPublications'), 
                Theme :: getInstance()->getCommonImagePath('Treemenu/Publication'), 
                $this->get_url(
                    array(
                        \Ehb\Application\Avilarts\Tool\Manager :: PARAM_BROWSE_PUBLICATION_TYPE => \Ehb\Application\Avilarts\Tool\Manager :: PUBLICATION_TYPE_FROM_ME)), 
                $type == \Ehb\Application\Avilarts\Tool\Manager :: PUBLICATION_TYPE_FROM_ME));
        $content[] = $publication_renderer->as_html();
        $content[] = '<div class="clear"></div>';
        
        if (method_exists($this->get_parent(), 'show_additional_information'))
        {
            $html[] = $this->get_parent()->show_additional_information($this);
        }
        $content[] = '</div>';
        
        $tabs->set_content(implode(PHP_EOL, $content));
        
        if ($this->get_publication_count() > 0 &&
             $this->get_parent()->get_tool_registration()->get_section_type() == CourseSection :: TYPE_DISABLED)
        {
            $html[] = Display :: warning_message(Translation :: get('ToolInvisible'));
        }
        
        $html[] = $tabs->render();
        
        $html[] = $this->render_footer();
        
        return implode(PHP_EOL, $html);
    }

    /**
     * Retrieves the publications
     * 
     * @return array An array of ContentObjectPublication objects
     */
    public function get_publications($offset, $max_objects, OrderBy $object_table_order)
    {
        if (empty($this->publications))
        {
            
            if ($this->get_publication_type() == \Ehb\Application\Avilarts\Tool\Manager :: PUBLICATION_TYPE_FROM_ME)
            {
                
                $publications_resultset = \Ehb\Application\Avilarts\Storage\DataManager :: retrieve_my_publications(
                    $this->get_location(), 
                    $this->get_entities(), 
                    $this->get_publication_conditions(), 
                    $object_table_order, 
                    $offset, 
                    $max_objects, 
                    $this->get_user_id());
            }
            elseif ($this->get_publication_type() == \Ehb\Application\Avilarts\Tool\Manager :: PUBLICATION_TYPE_ALL)
            {
                $publications_resultset = \Ehb\Application\Avilarts\Storage\DataManager :: retrieve_content_object_publications(
                    $this->get_publication_conditions(), 
                    $object_table_order, 
                    $offset, 
                    $max_objects);
            }
            else
            {
                $publications_resultset = \Ehb\Application\Avilarts\Storage\DataManager :: retrieve_content_object_publications_with_view_right_granted_in_category_location(
                    $this->get_location(), 
                    $this->get_entities(), 
                    $this->get_publication_conditions(), 
                    $object_table_order, 
                    $offset, 
                    $max_objects, 
                    $this->get_user_id());
            }
            if ($publications_resultset)
            {
                $this->publications = $publications_resultset->as_array();
            }
        }
        
        if ($this->publications)
        {
            return $this->publications;
        }
    }

    /**
     * Retrieves the number of published content objects
     * 
     * @return int
     */
    public function get_publication_count()
    {
        if ($this->get_publication_type() == \Ehb\Application\Avilarts\Tool\Manager :: PUBLICATION_TYPE_FROM_ME)
        {
            $count = \Ehb\Application\Avilarts\Storage\DataManager :: count_my_publications(
                $this->get_location(), 
                $this->get_entities(), 
                $this->get_publication_conditions(), 
                $this->get_user_id());
        }
        elseif ($this->get_publication_type() == \Ehb\Application\Avilarts\Tool\Manager :: PUBLICATION_TYPE_ALL)
        {
            $count = \Ehb\Application\Avilarts\Storage\DataManager :: count_content_object_publications(
                $this->get_publication_conditions());
        }
        else
        {
            $count = \Ehb\Application\Avilarts\Storage\DataManager :: count_content_object_publications_with_view_right_granted_in_category_location(
                $this->get_location(), 
                $this->get_entities(), 
                $this->get_publication_conditions(), 
                $this->get_user_id());
        }
        return $count;
    }

    public function getButtonToolbarRenderer()
    {
        if (! isset($this->buttonToolbarRenderer))
        {
            $buttonToolbar = new ButtonToolBar($this->get_url());
            $commonActions = new ButtonGroup();
            $toolActions = new ButtonGroup();
            
            if ($this->is_allowed(\Ehb\Application\Avilarts\Rights\Rights :: ADD_RIGHT))
            {
                $publish_type = PlatformSetting :: get('display_publication_screen', __NAMESPACE__);
                if ($publish_type == \Ehb\Application\Avilarts\Tool\Manager :: PUBLISH_TYPE_BOTH)
                {
                    $commonActions->addButton(
                        new Button(
                            Translation :: get('QuickPublish', null, Utilities :: COMMON_LIBRARIES), 
                            Theme :: getInstance()->getCommonImagePath('Action/Publish'), 
                            $this->get_url(
                                array(
                                    \Ehb\Application\Avilarts\Tool\Manager :: PARAM_ACTION => \Ehb\Application\Avilarts\Tool\Manager :: ACTION_PUBLISH, 
                                    \Ehb\Application\Avilarts\Tool\Manager :: PARAM_PUBLISH_MODE => \Ehb\Application\Avilarts\Tool\Manager :: PUBLISH_MODE_QUICK)), 
                            ToolbarItem :: DISPLAY_ICON_AND_LABEL));
                }
                
                // added tool dependent publish button
                $tool_dependent_publish = PlatformSetting :: get('tool_dependent_publish_button', __NAMESPACE__);
                
                if ($tool_dependent_publish == \Ehb\Application\Avilarts\Tool\Manager :: PUBLISH_INDEPENDENT)
                {
                    $commonActions->addButton(
                        new Button(
                            Translation :: get('Publish', null, Utilities :: COMMON_LIBRARIES), 
                            Theme :: getInstance()->getCommonImagePath('Action/Publish'), 
                            $this->get_url(
                                array(
                                    \Ehb\Application\Avilarts\Tool\Manager :: PARAM_ACTION => \Ehb\Application\Avilarts\Tool\Manager :: ACTION_PUBLISH)), 
                            ToolbarItem :: DISPLAY_ICON_AND_LABEL));
                }
                else
                {
                    $tool = Request :: get('tool');
                    $commonActions->addButton(
                        new Button(
                            Translation :: get(
                                'PublishToolDependent', 
                                array(
                                    'TYPE' => Translation :: get(
                                        'TypeNameSingle', 
                                        null, 
                                        'Ehb\Application\Avilarts\Tool\Implementation\\' . $tool)), 
                                Utilities :: COMMON_LIBRARIES), 
                            Theme :: getInstance()->getCommonImagePath('Action/Publish'), 
                            $this->get_url(
                                array(
                                    \Ehb\Application\Avilarts\Tool\Manager :: PARAM_ACTION => \Ehb\Application\Avilarts\Tool\Manager :: ACTION_PUBLISH)), 
                            ToolbarItem :: DISPLAY_ICON_AND_LABEL));
                }
            }
            
            if ($this->is_course_admin)
            {
                $commonActions->addButton(
                    new Button(
                        Translation :: get('ManageRights', null, Utilities :: COMMON_LIBRARIES), 
                        Theme :: getInstance()->getCommonImagePath('Action/Rights'), 
                        $this->get_url(
                            array(
                                \Ehb\Application\Avilarts\Tool\Manager :: PARAM_ACTION => \Ehb\Application\Avilarts\Tool\Manager :: ACTION_EDIT_RIGHTS, 
                                \Ehb\Application\Avilarts\Manager :: PARAM_CATEGORY => Request :: get(
                                    \Ehb\Application\Avilarts\Manager :: PARAM_CATEGORY))), 
                        ToolbarItem :: DISPLAY_ICON_AND_LABEL));
            }
            
            $commonActions->addButton(
                new Button(
                    Translation :: get('ShowAll', null, Utilities :: COMMON_LIBRARIES), 
                    Theme :: getInstance()->getCommonImagePath('Action/Browser'), 
                    $this->get_url(array(\Ehb\Application\Avilarts\Tool\Manager :: PARAM_ACTION => null)), 
                    ToolbarItem :: DISPLAY_ICON_AND_LABEL));
            
            if ($this->is_course_admin && $this->get_parent() instanceof Categorizable)
            {
                $commonActions->addButton(
                    new Button(
                        Translation :: get('ManageCategories', null, Utilities :: COMMON_LIBRARIES), 
                        Theme :: getInstance()->getCommonImagePath('Action/Category'), 
                        $this->get_url(
                            array(
                                \Ehb\Application\Avilarts\Tool\Manager :: PARAM_ACTION => \Ehb\Application\Avilarts\Tool\Manager :: ACTION_MANAGE_CATEGORIES)), 
                        ToolbarItem :: DISPLAY_ICON_AND_LABEL));
            }
            
            $course_settings_controller = CourseSettingsController :: get_instance();
            
            if (! $this->introduction_text && $this->is_course_admin && $course_settings_controller->get_course_setting(
                $this->get_course_id(), 
                CourseSettingsConnector :: ALLOW_INTRODUCTION_TEXT))
            {
                $commonActions->addButton(
                    new Button(
                        Translation :: get('PublishIntroductionText', null, Utilities :: COMMON_LIBRARIES), 
                        Theme :: getInstance()->getCommonImagePath('Action/Introduce'), 
                        $this->get_url(
                            array(
                                \Ehb\Application\Avilarts\Tool\Manager :: PARAM_ACTION => \Ehb\Application\Avilarts\Tool\Manager :: ACTION_PUBLISH_INTRODUCTION)), 
                        ToolbarItem :: DISPLAY_ICON_AND_LABEL));
            }
            
            if (method_exists($this->get_parent(), 'get_tool_actions'))
            {
                $toolActions->addButton(new Button($this->get_parent()->get_tool_actions()));
            }
            
            $browser_types = $this->get_parent()->get_available_browser_types();
            
            if (count($browser_types) > 1)
            {
                foreach ($browser_types as $browser_type)
                {
                    $toolActions->addButton(
                        new Button(
                            Translation :: get(
                                (string) StringUtilities :: getInstance()->createString($browser_type)->upperCamelize() .
                                     'View', 
                                    null, 
                                    Utilities :: COMMON_LIBRARIES), 
                            Theme :: getInstance()->getCommonImagePath('View/' . $browser_type), 
                            $this->get_url(
                                array(\Ehb\Application\Avilarts\Tool\Manager :: PARAM_BROWSER_TYPE => $browser_type)), 
                            ToolbarItem :: DISPLAY_ICON_AND_LABEL));
                }
            }
            
            $buttonToolbar->addButtonGroup($toolActions);
            $buttonToolbar->addButtonGroup($commonActions);
            
            $this->buttonToolbarRenderer = new ButtonToolBarRenderer($buttonToolbar);
        }
        
        return $this->buttonToolbarRenderer;
    }

    public function get_publication_conditions()
    {
        $conditions = array();
        
        $type = $this->get_publication_type();
        switch ($type)
        {
            // Begin with the publisher condition when FROM_ME and add the
            // remaining conditions. Skip the publisher
            // condition when ALL.
            case \Ehb\Application\Avilarts\Tool\Manager :: PUBLICATION_TYPE_FROM_ME :
                $va_id = Session :: get(\Ehb\Application\Avilarts\Tool\Manager :: PARAM_VIEW_AS_ID);
                $course_id = Session :: get(\Ehb\Application\Avilarts\Tool\Manager :: PARAM_VIEW_AS_COURSE_ID);
                $user_id = Session :: get_user_id();
                
                $publisher_id = (isset($va_id) && isset($course_id) && $course_id == $this->get_course_id()) ? $va_id : $user_id;
                
                $conditions[] = new EqualityCondition(
                    new PropertyConditionVariable(
                        ContentObjectPublication :: class_name(), 
                        ContentObjectPublication :: PROPERTY_PUBLISHER_ID), 
                    new StaticConditionVariable($publisher_id));
            
            case \Ehb\Application\Avilarts\Tool\Manager :: PUBLICATION_TYPE_ALL :
                $conditions[] = new EqualityCondition(
                    new PropertyConditionVariable(
                        ContentObjectPublication :: class_name(), 
                        ContentObjectPublication :: PROPERTY_COURSE_ID), 
                    new StaticConditionVariable($this->get_course_id()));
                
                if ($this->get_tool_id())
                {
                    $conditions[] = new EqualityCondition(
                        new PropertyConditionVariable(
                            ContentObjectPublication :: class_name(), 
                            ContentObjectPublication :: PROPERTY_TOOL), 
                        new StaticConditionVariable($this->get_tool_id()));
                }
                
                $category_id = Request :: get(\Ehb\Application\Avilarts\Manager :: PARAM_CATEGORY);
                if (! $category_id)
                {
                    $category_id = 0;
                }
                
                $conditions[] = new EqualityCondition(
                    new PropertyConditionVariable(
                        ContentObjectPublication :: class_name(), 
                        ContentObjectPublication :: PROPERTY_CATEGORY_ID), 
                    new StaticConditionVariable($category_id));
                
                break;
            default :
                
                $from_date_variables = new PropertyConditionVariable(
                    ContentObjectPublication :: class_name(), 
                    ContentObjectPublication :: PROPERTY_FROM_DATE);
                
                $to_date_variable = new PropertyConditionVariable(
                    ContentObjectPublication :: class_name(), 
                    ContentObjectPublication :: PROPERTY_TO_DATE);
                
                $time_conditions = array();
                
                $time_conditions[] = new EqualityCondition(
                    new PropertyConditionVariable(
                        ContentObjectPublication :: class_name(), 
                        ContentObjectPublication :: PROPERTY_HIDDEN), 
                    new StaticConditionVariable(0));
                
                $forever_conditions = array();
                
                $forever_conditions[] = new EqualityCondition($from_date_variables, new StaticConditionVariable(0));
                
                $forever_conditions[] = new EqualityCondition($to_date_variable, new StaticConditionVariable(0));
                
                $forever_condition = new AndCondition($forever_conditions);
                
                $between_conditions = array();
                
                $between_conditions[] = new InequalityCondition(
                    $from_date_variables, 
                    InequalityCondition :: LESS_THAN_OR_EQUAL, 
                    new StaticConditionVariable(time()));
                
                $between_conditions[] = new InequalityCondition(
                    $to_date_variable, 
                    InequalityCondition :: GREATER_THAN_OR_EQUAL, 
                    new StaticConditionVariable(time()));
                
                $between_condition = new AndCondition($between_conditions);
                
                $time_conditions[] = new OrCondition(array($forever_condition, $between_condition));
                
                $conditions[] = new AndCondition($time_conditions);
                break;
        }
        
        if ($this->get_search_condition())
        {
            $conditions[] = $this->get_search_condition();
        }
        
        $conditions[] = new InCondition(
            new PropertyConditionVariable(ContentObject :: class_name(), ContentObject :: PROPERTY_TYPE), 
            $this->get_allowed_types());
        
        if (method_exists($this->get_parent(), 'get_tool_conditions'))
        {
            foreach ($this->get_parent()->get_tool_conditions() as $tool_condition)
            {
                $conditions[] = $tool_condition;
            }
        }
        
        if ($conditions)
        {
            return new AndCondition($conditions);
        }
        else
        {
            return null;
        }
    }

    public function get_search_condition()
    {
        $query = $this->buttonToolbarRenderer->getSearchForm()->getQuery();
        if (isset($query) && $query != '')
        {
            $conditions[] = new PatternMatchCondition(
                new PropertyConditionVariable(ContentObject :: class_name(), ContentObject :: PROPERTY_TITLE), 
                '*' . $query . '*');
            
            $conditions[] = new PatternMatchCondition(
                new PropertyConditionVariable(ContentObject :: class_name(), ContentObject :: PROPERTY_DESCRIPTION), 
                '*' . $query . '*');
            
            return new OrCondition($conditions);
        }
        
        return null;
    }

    public function count_tool_categories()
    {
        $conditions = array();
        
        $conditions[] = new EqualityCondition(
            new PropertyConditionVariable(
                ContentObjectPublicationCategory :: class_name(), 
                ContentObjectPublicationCategory :: PROPERTY_TOOL), 
            new StaticConditionVariable($this->get_tool_id()));
        
        $conditions[] = new EqualityCondition(
            new PropertyConditionVariable(
                ContentObjectPublicationCategory :: class_name(), 
                ContentObjectPublicationCategory :: PROPERTY_COURSE), 
            new StaticConditionVariable($this->get_course_id()));
        
        $condition = new AndCondition($conditions);
        
        return \Ehb\Application\Avilarts\Storage\DataManager :: count(
            ContentObjectPublicationCategory :: class_name(), 
            $condition);
    }

    private function retrieve_category($category_id)
    {
        $conditions[] = new EqualityCondition(
            new PropertyConditionVariable(
                ContentObjectPublicationCategory :: class_name(), 
                ContentObjectPublicationCategory :: PROPERTY_ID), 
            new StaticConditionVariable($category_id));
        
        $conditions[] = new EqualityCondition(
            new PropertyConditionVariable(
                ContentObjectPublicationCategory :: class_name(), 
                ContentObjectPublicationCategory :: PROPERTY_COURSE), 
            new StaticConditionVariable($this->get_parent()->get_course_id()));
        
        $conditions[] = new EqualityCondition(
            new PropertyConditionVariable(
                ContentObjectPublicationCategory :: class_name(), 
                ContentObjectPublicationCategory :: PROPERTY_TOOL), 
            new StaticConditionVariable($this->get_parent()->get_tool_id()));
        
        $condition = new AndCondition($conditions);
        
        $objects = \Ehb\Application\Avilarts\Storage\DataManager :: retrieves(
            ContentObjectPublicationCategory :: class_name(), 
            new DataClassRetrievesParameters($condition));
        
        return $objects->next_result();
    }

    public function get_publication_type()
    {
        $type = Request :: get(\Ehb\Application\Avilarts\Tool\Manager :: PARAM_BROWSE_PUBLICATION_TYPE);
        if (! $type)
        {
            if ($this->is_course_admin)
            {
                $type = \Ehb\Application\Avilarts\Tool\Manager :: PUBLICATION_TYPE_ALL;
            }
            else
            {
                $type = \Ehb\Application\Avilarts\Tool\Manager :: PUBLICATION_TYPE_FOR_ME;
            }
        }
        
        return $type;
    }

    /**
     * Returns the default object table order for the browser.
     * Can be "overridden" by the individual component to force
     * a different order if needed. Because the individual component is not an actual implementation but merely this
     * parent, there is a check if the method exists.
     * 
     * @return ObjectTableOrder
     */
    public function get_default_order_property()
    {
        if (method_exists($this->get_parent(), 'get_default_order_property'))
        {
            return $this->get_parent()->get_default_order_property();
        }
        return new OrderBy(
            new PropertyConditionVariable(
                ContentObjectPublication :: class_name(), 
                ContentObjectPublication :: PROPERTY_DISPLAY_ORDER_INDEX));
    }

    public function tool_category_has_new_publications($category_id)
    {
        return \Ehb\Application\Avilarts\Storage\DataManager :: tool_category_has_new_publications(
            $this->get_tool_id(), 
            $this->get_user(), 
            $this->get_course(), 
            $category_id);
    }
}
