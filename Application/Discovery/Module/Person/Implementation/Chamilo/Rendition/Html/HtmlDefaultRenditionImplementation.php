<?php
namespace Ehb\Application\Discovery\Module\Person\Implementation\Chamilo\Rendition\Html;

use Chamilo\Libraries\Architecture\ClassnameUtilities;
use Chamilo\Libraries\Format\Display;
use Chamilo\Libraries\Format\Structure\ActionBar\ActionBarSearchForm;
use Chamilo\Libraries\Format\Structure\ActionBar\Button;
use Chamilo\Libraries\Format\Structure\ActionBar\ButtonGroup;
use Chamilo\Libraries\Format\Structure\ActionBar\ButtonToolBar;
use Chamilo\Libraries\Format\Structure\ActionBar\Renderer\ButtonToolBarRenderer;
use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
use Chamilo\Libraries\Format\Structure\ToolbarItem;
use Chamilo\Libraries\Format\Table\Interfaces\TableSupport;
use Chamilo\Libraries\Format\Tabs\DynamicContentTab;
use Chamilo\Libraries\Format\Tabs\DynamicTabsRenderer;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Session\Session;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Storage\Parameters\DataClassCountParameters;
use Chamilo\Libraries\Storage\Parameters\DataClassDistinctParameters;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrieveParameters;
use Chamilo\Libraries\Storage\Query\Condition\AndCondition;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\Condition\InCondition;
use Chamilo\Libraries\Storage\Query\Condition\OrCondition;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Chamilo\Libraries\Utilities\Utilities;
use Ehb\Application\Discovery\Module\Person\Implementation\Chamilo\GroupBrowser\GroupBrowserTable;
use Ehb\Application\Discovery\Module\Person\Implementation\Chamilo\GroupRelUserBrowser\GroupRelUserBrowserTable;
use Ehb\Application\Discovery\Module\Person\Implementation\Chamilo\Menu\GroupMenu;
use Ehb\Application\Discovery\Module\Person\Implementation\Chamilo\Rendition\RenditionImplementation;
use Ehb\Application\Discovery\Module\Person\Implementation\Chamilo\Rights;
use Ehb\Application\Discovery\Module\Person\Implementation\Chamilo\UserBrowser\UserBrowserTable;
use Ehb\Application\Discovery\RightsGroupEntityRight;

class HtmlDefaultRenditionImplementation extends RenditionImplementation implements TableSupport
{
    const TAB_SUBGROUPS = 0;
    const TAB_USERS = 1;
    const TAB_DETAILS = 2;

    /**
     *
     * @var ButtonToolBarRenderer
     */
    private $buttonToolbarRenderer;

    private $allowed_groups;

    private $current_group;

    public function render()
    {
        BreadcrumbTrail::getInstance()->add(new Breadcrumb(null, Translation::get(TypeName)));
        
        $this->buttonToolbarRenderer = $this->getButtonToolbarRenderer();
        $html[] = $this->buttonToolbarRenderer->render() . '<br />';
        $html[] = $this->get_menu_html();
        $html[] = $this->get_user_html();
        
        return implode(PHP_EOL, $html);
    }

    public function getButtonToolbarRenderer()
    {
        $parameters = $this->get_application()->get_parameters(true);
        $parameters[\Ehb\Application\Discovery\Manager::PARAM_MODULE_ID] = $this->get_module_instance()->get_id();
        
        if (! isset($this->buttonToolbarRenderer))
        {
            $buttonToolbar = new ButtonToolBar($this->get_application()->get_url($parameters));
            $commonActions = new ButtonGroup();
            
            $commonActions->addButton(
                new Button(
                    Translation::get('Show', null, Utilities::COMMON_LIBRARIES), 
                    Theme::getInstance()->getCommonImagePath('Action/Browser'), 
                    $this->get_application()->get_url(), 
                    ToolbarItem::DISPLAY_ICON_AND_LABEL));
            $buttonToolbar->addButtonGroup($commonActions);
            
            $this->buttonToolbarRenderer = new ButtonToolBarRenderer($buttonToolbar);
        }
        
        return $this->buttonToolbarRenderer;
    }

    public function get_menu_html()
    {
        $url = $this->get_application()->get_url(
            array(
                \Ehb\Application\Discovery\Manager::PARAM_MODULE_ID => $this->get_module_instance()->get_id(), 
                \Chamilo\Core\Group\Manager::PARAM_GROUP_ID => '%s'));
        $group_menu = new GroupMenu($this, urldecode($url));
        
        $html = array();
        $html[] = '<div style="float: left; width: 25%; overflow: auto; height: 500px;">';
        $html[] = $group_menu->render_as_tree();
        $html[] = '</div>';
        
        return implode($html, "\n");
    }

    public function get_current_group()
    {
        if (! isset($this->current_group))
        {
            if ($this->get_group() == '0' || is_null($this->get_group()))
            {
                $condition = new EqualityCondition(
                    new PropertyConditionVariable(
                        \Chamilo\Core\Group\Storage\DataClass\Group::class_name(), 
                        \Chamilo\Core\Group\Storage\DataClass\Group::PROPERTY_PARENT_ID), 
                    new StaticConditionVariable(0));
                $group = \Chamilo\Core\Group\Storage\DataManager::retrieve(
                    \Chamilo\Core\Group\Storage\DataClass\Group::class_name(), 
                    new DataClassRetrieveParameters($condition));
                $this->current_group = $group;
            }
            else
            {
                $this->current_group = \Chamilo\Core\Group\Storage\DataManager::retrieve_by_id(
                    \Chamilo\Core\Group\Storage\DataClass\Group::class_name(), 
                    (int) $this->get_group());
            }
        }
        
        return $this->current_group;
    }

    public function get_user_html()
    {
        $current_group = $this->get_current_group();
        $show_users = false;
        $show_subgroups = false;
        if (! $this->get_context()->get_user()->is_platform_admin())
        {
            foreach ($this->get_allowed_groups() as $allowed_group_id)
            {
                if ($current_group->is_child_of($allowed_group_id) || $current_group->is_parent_of($allowed_group_id) ||
                     $allowed_group_id == $current_group->get_id())
                {
                    $show_subgroups = true;
                    
                    if ($current_group->is_child_of($allowed_group_id) || $allowed_group_id == $current_group->get_id())
                    {
                        $show_users = true;
                        break;
                    }
                    else
                    {
                        $show_users = false;
                    }
                }
            }
        }
        else
        {
            $show_users = true;
            $show_subgroups = true;
        }
        
        $html = array();
        $html[] = '<div style="float: right; width: 73%;">';
        
        $renderer_name = ClassnameUtilities::getInstance()->getClassnameFromObject($this, true);
        $tabs = new DynamicTabsRenderer($renderer_name);
        
        $parameters = $this->get_application()->get_parameters();
        $parameters[ActionBarSearchForm::PARAM_SIMPLE_SEARCH_QUERY] = $this->buttonToolbarRenderer->getSearchForm()->getQuery();
        $parameters[\Chamilo\Core\Group\Manager::PARAM_GROUP_ID] = $this->get_group();
        $parameters[\Ehb\Application\Discovery\Manager::PARAM_MODULE_ID] = $this->get_module_instance()->get_id();
        
        $query = $this->buttonToolbarRenderer->getSearchForm()->getQuery();
        if (isset($query) && $query != '')
        {
            $count_users = \Chamilo\Core\User\Storage\DataManager::count(
                \Chamilo\Core\User\Storage\DataClass\User::class_name(), 
                new DataClassCountParameters(
                    $this->get_users_condition($this->buttonToolbarRenderer->getSearchForm()->getQuery())));
            $count_groups = \Chamilo\Core\Group\Storage\DataManager::count(
                \Chamilo\Core\Group\Storage\DataClass\Group::class_name(), 
                new DataClassCountParameters(
                    $this->get_subgroups_condition($this->buttonToolbarRenderer->getSearchForm()->getQuery())));
            
            if ($count_users > 0)
            {
                $table = new UserBrowserTable($this);
                $tabs->add_tab(
                    new DynamicContentTab(
                        self::TAB_USERS, 
                        Translation::get('Users', null, 'user'), 
                        Theme::getInstance()->getImagesPath(__NAMESPACE__) . 'Tab/users.png', 
                        $table->as_html()));
            }
            
            if ($count_groups > 0)
            {
                $table = new GroupBrowserTable(
                    $this, 
                    $parameters, 
                    $this->get_subgroups_condition($this->buttonToolbarRenderer->getSearchForm()->getQuery()));
                $tabs->add_tab(
                    new DynamicContentTab(
                        self::TAB_SUBGROUPS, 
                        Translation::get('Subgroups'), 
                        Theme::getInstance()->getImagesPath(__NAMESPACE__) . 'Tab/groups.png', 
                        $table->as_html()));
            }
            
            if ($count_users == 0 && $count_groups == 0)
            {
                $html[] = Display::warning_message(
                    Translation::get('NoSearchResults', null, Utilities::COMMON_LIBRARIES), 
                    true);
            }
        }
        else
        {
            if ($show_users)
            {
                $table = new GroupRelUserBrowserTable(
                    $this, 
                    $parameters, 
                    $this->get_users_condition($this->buttonToolbarRenderer->getSearchForm()->getQuery()));
                $tabs->add_tab(
                    new DynamicContentTab(
                        self::TAB_USERS, 
                        Translation::get('Users', null, 'user'), 
                        Theme::getInstance()->getImagesPath(__NAMESPACE__) . 'Tab/users.png', 
                        $table->as_html()));
            }
            
            if ($show_subgroups)
            {
                $count_groups = \Chamilo\Core\Group\Storage\DataManager::count(
                    \Chamilo\Core\Group\Storage\DataClass\Group::class_name(), 
                    new DataClassCountParameters(
                        $this->get_subgroups_condition($this->buttonToolbarRenderer->getSearchForm()->getQuery())));
                if ($count_groups > 0)
                {
                    $table = new GroupBrowserTable(
                        $this, 
                        $parameters, 
                        $this->get_subgroups_condition($this->buttonToolbarRenderer->getSearchForm()->getQuery()));
                    $tabs->add_tab(
                        new DynamicContentTab(
                            self::TAB_SUBGROUPS, 
                            Translation::get('Subgroups'), 
                            Theme::getInstance()->getImagesPath(__NAMESPACE__) . 'Tab/groups.png', 
                            $table->as_html()));
                }
            }
            
            $tabs->add_tab(
                new DynamicContentTab(
                    self::TAB_DETAILS, 
                    Translation::get('Details'), 
                    Theme::getInstance()->getImagesPath(__NAMESPACE__) . 'Tab/details.png', 
                    $this->get_group_info()));
        }
        
        $html[] = $tabs->render();
        
        $html[] = '</div>';
        $html[] = '<div class="clear"></div>';
        
        return implode($html, "\n");
    }

    public function get_group_info()
    {
        $group_id = $this->get_group();
        $group = \Chamilo\Core\Group\Storage\DataManager::retrieve_by_id(
            \Chamilo\Core\Group\Storage\DataClass\Group::class_name(), 
            (int) $group_id);
        
        $html = array();
        
        $html[] = '<b>' . Translation::get('Code') . '</b>: ' . $group->get_code() . '<br />';
        
        $description = $group->get_description();
        if ($description)
        {
            $html[] = '<b>' . Translation::get('Description', null, Utilities::COMMON_LIBRARIES) . '</b>: ' .
                 $description . '<br />';
        }
        
        $html[] = '<br />';
        
        return implode(PHP_EOL, $html);
    }

    public function get_group_viewing_url($group)
    {
        return $this->get_application()->get_url(
            array(
                \Ehb\Application\Discovery\Manager::PARAM_MODULE_ID => $this->get_module_instance()->get_id(), 
                \Chamilo\Core\Group\Manager::PARAM_GROUP_ID => $group->get_id()));
    }

    /*
     * (non-PHPdoc) @see \libraries\NewObjectTableSupport::get_object_table_condition()
     */
    public function get_object_table_condition($object_table_class_name)
    {
        return $this->get_users_condition($this->buttonToolbarRenderer->getSearchForm()->getQuery());
    }

    public function get_parameters()
    {
        $parameters = $this->get_application()->get_parameters();
        $parameters[ActionBarSearchForm::PARAM_SIMPLE_SEARCH_QUERY] = $this->buttonToolbarRenderer->getSearchForm()->getQuery();
        $parameters[\Chamilo\Core\Group\Manager::PARAM_GROUP_ID] = $this->get_group();
        $parameters[\Ehb\Application\Discovery\Manager::PARAM_MODULE_ID] = $this->get_module_instance()->get_id();
        return $parameters;
    }

    /*
     * (non-PHPdoc) @see \application\discovery\AbstractRenditionImplementation::get_format()
     */
    public function get_format()
    {
        return \Ehb\Application\Discovery\Rendition\Rendition::FORMAT_HTML;
    }

    /*
     * (non-PHPdoc) @see \application\discovery\AbstractRenditionImplementation::get_view()
     */
    public function get_view()
    {
        return \Ehb\Application\Discovery\Rendition\Rendition::VIEW_DEFAULT;
    }

    public function get_allowed_groups()
    {
        if (! isset($this->allowed_groups))
        {
            $current_user_group_ids = $this->get_context()->get_user()->get_groups(true);
            
            $conditions = array();
            $conditions[] = new EqualityCondition(
                new PropertyConditionVariable(
                    RightsGroupEntityRight::class_name(), 
                    RightsGroupEntityRight::PROPERTY_MODULE_ID), 
                new StaticConditionVariable($this->get_module_instance()->get_id()));
            $conditions[] = new EqualityCondition(RightsGroupEntityRight::PROPERTY_RIGHT_ID, Rights::VIEW_RIGHT);
            
            $entities_conditions = array();
            
            $user_entity_conditions = array();
            $user_entity_conditions[] = new EqualityCondition(
                new PropertyConditionVariable(
                    RightsGroupEntityRight::class_name(), 
                    RightsGroupEntityRight::PROPERTY_ENTITY_ID), 
                new StaticConditionVariable(Session::get_user_id()));
            $user_entity_conditions[] = new EqualityCondition(
                new PropertyConditionVariable(
                    RightsGroupEntityRight::class_name(), 
                    RightsGroupEntityRight::PROPERTY_ENTITY_TYPE), 
                new StaticConditionVariable(\Chamilo\Core\Rights\Entity\UserEntity::ENTITY_TYPE));
            $entities_conditions[] = new AndCondition($user_entity_conditions);
            
            $group_entity_conditions = array();
            $group_entity_conditions[] = new InCondition(
                new PropertyConditionVariable(
                    RightsGroupEntityRight::class_name(), 
                    RightsGroupEntityRight::PROPERTY_ENTITY_ID), 
                $current_user_group_ids);
            $group_entity_conditions[] = new EqualityCondition(
                new PropertyConditionVariable(
                    RightsGroupEntityRight::class_name(), 
                    RightsGroupEntityRight::PROPERTY_ENTITY_TYPE), 
                new StaticConditionVariable(\Chamilo\Core\Rights\Entity\PlatformGroupEntity::ENTITY_TYPE));
            $entities_conditions[] = new AndCondition($group_entity_conditions);
            
            $conditions[] = new OrCondition($entities_conditions);
            $condition = new AndCondition($conditions);
            
            $this->allowed_groups = \Ehb\Application\Discovery\Storage\DataManager::distinct(
                RightsGroupEntityRight::class_name(), 
                new DataClassDistinctParameters($condition, RightsGroupEntityRight::PROPERTY_GROUP_ID));
        }
        
        return $this->allowed_groups;
    }

    /*
     * (non-PHPdoc) @see \libraries\format\TableSupport::get_table_condition()
     */
    public function get_table_condition($table_class_name)
    {
        // TODO Auto-generated method stub
    }
}
