<?php
namespace application\discovery\rights_editor_manager;

use common\libraries\SortableTableFromArray;

use application\discovery\UserEntity;

use common\libraries\Translation;

use common\libraries\DynamicVisualTab;

use common\libraries\DynamicVisualTabsRenderer;

use application\discovery\PlatformGroupEntity;

use common\libraries\InCondition;

use application\discovery\DiscoveryDataManager;

use common\libraries\AndCondition;

use application\discovery\RightsGroupEntityRight;

use application\discovery\DiscoveryManager;

use group\GroupDataManager;

use group\GroupMenu;

use group\GroupManager;

use common\libraries\Request;

use group\Group;

use common\libraries\EqualityCondition;

class RightsEditorManagerAdvancedRightsEditorComponent extends RightsEditorManager
{
    private $group;
    private $root_group;
    private $entity;
    
    const PARAM_ENTITY = 'entity';

    function run()
    {
        $menu = $this->get_menu_html();
        
        $this->display_header();
        echo $menu;
        echo $this->get_rights();
        $this->display_footer();
    }

    function get_menu_html()
    {
        $url = $this->get_url(array(GroupManager :: PARAM_GROUP_ID => '%s'));
        $group_menu = new GroupMenu($this->get_group(), urldecode($url));
        //$group_menu = new TreeMenu('GroupTreeMenu', new GroupTreeMenuDataProvider($this->get_url(), $this->get_group()));
        $html = array();
        $html[] = '<div style="float: left; width: 18%; overflow: auto; height: 500px;">';
        $html[] = $group_menu->render_as_tree();
        $html[] = '</div>';
        
        return implode($html, "\n");
    }

    function get_rights()
    {
        $tabs = new DynamicVisualTabsRenderer('rights', $this->get_table()->as_html());
        $param = array();
        $param[GroupManager :: PARAM_GROUP_ID] = $this->get_group();
        $param[self :: PARAM_ENTITY] = PlatformGroupEntity :: ENTITY_TYPE;
        $selected = $this->get_entity() == PlatformGroupEntity :: ENTITY_TYPE ? true : false;
        $tabs->add_tab(new DynamicVisualTab('group', Translation :: get('GroupRights'), null, $this->get_url($param), $selected));
        
        $param[self :: PARAM_ENTITY] = UserEntity :: ENTITY_TYPE;
        $selected = $this->get_entity() == UserEntity :: ENTITY_TYPE ? true : false;
        $tabs->add_tab(new DynamicVisualTab('user', Translation :: get('UserRights'), null, $this->get_url($param), $selected));
        
        $param[self :: PARAM_ENTITY] = 0;
        $selected = $this->get_entity() == 0 ? true : false;
        $tabs->add_tab(new DynamicVisualTab('everyone', Translation :: get('EveryoneRights'), null, $this->get_url($param), $selected));
        
        $html = array();
        $html[] = '<div style="float: right; width: 80%;">';
        $html[] = $tabs->render();
        $html[] = '</div>';
        
        return implode($html, "\n");
    }

    function get_table()
    {
        if ($this->get_entity() == PlatformGroupEntity :: ENTITY_TYPE)
        {
            return new GroupRightBrowserTable($this, $this->get_parameters(), $this->get_condition());
        }
        elseif ($this->get_entity() == UserEntity :: ENTITY_TYPE)
        {
            return new UserRightBrowserTable($this, $this->get_parameters(), $this->get_condition());
        }
        else
        {
            foreach ($this->get_available_rights() as $right_name => $right_id)
            {
                $group_id = $this->get_group();
                $module_id = $this->get_parent()->get_module_instance_id();
                
                $conditions[] = new EqualityCondition(RightsGroupEntityRight :: PROPERTY_RIGHT_ID, $right_id);
                $conditions[] = new EqualityCondition(RightsGroupEntityRight :: PROPERTY_GROUP_ID, $group_id);
                $conditions[] = new EqualityCondition(RightsGroupEntityRight :: PROPERTY_MODULE_ID, $module_id);
                $conditions[] = new EqualityCondition(RightsGroupEntityRight :: PROPERTY_ENTITY_ID, 0);
                $conditions[] = new EqualityCondition(RightsGroupEntityRight :: PROPERTY_ENTITY_TYPE, 0);
                
                $condition = new AndCondition($conditions);
                
                $count = DiscoveryDataManager :: get_instance()->count_rights_group_entity_rights($condition);
                
                if ($count >= 1)
                {
                    $table_data[0][] = '<div class="rightTrue"></div>';
                }
                else
                {
                    $table_data[0][] = '<div class="rightFalse"></div>';
                }
            
            }
            $table = new SortableTableFromArray($table_data);
            $column = 0;
            foreach ($this->get_available_rights() as $right_name => $right_id)
            {
                $table->set_header($column, $right_name);
                $column ++;
            }
            
            return $table;
        }
    
    }

    function get_entity()
    {
        if (! isset($this->entity))
        {
            $this->entity = Request :: get(self :: PARAM_ENTITY);
            
            if (! isset($this->entity))
            {
                $this->entity = PlatformGroupEntity :: ENTITY_TYPE;
            }
        
        }
        
        return $this->entity;
    }

    function get_condition()
    {
        $group_conditions = array();
        $group_conditions[] = new EqualityCondition(RightsGroupEntityRight :: PROPERTY_MODULE_ID, Request :: get(DiscoveryManager :: PARAM_MODULE_ID));
        $group_conditions[] = new EqualityCondition(RightsGroupEntityRight :: PROPERTY_GROUP_ID, Request :: get(GroupManager :: PARAM_GROUP_ID));
        
        if ($this->get_entity() == PlatformGroupEntity :: ENTITY_TYPE)
        {
            $group_conditions[] = new EqualityCondition(RightsGroupEntityRight :: PROPERTY_ENTITY_TYPE, PlatformGroupEntity :: ENTITY_TYPE);
        }
        else
        {
            $group_conditions[] = new EqualityCondition(RightsGroupEntityRight :: PROPERTY_ENTITY_TYPE, UserEntity :: ENTITY_TYPE);
        }
        $group_condition = new AndCondition($group_conditions);
        
        $group_ids = DiscoveryDataManager :: get_instance()->retrieve_distinct(RightsGroupEntityRight :: get_table_name(), RightsGroupEntityRight :: PROPERTY_ENTITY_ID, $group_condition);
        return new InCondition(Group :: PROPERTY_ID, $group_ids);
    }

    function get_group()
    {
        if (! $this->group)
        {
            $this->group = Request :: get(GroupManager :: PARAM_GROUP_ID);
            
            if (! $this->group)
            {
                $this->group = $this->get_root_group()->get_id();
            }
        }
        
        return $this->group;
    }

    function get_root_group()
    {
        if (! $this->root_group)
        {
            $group = GroupDataManager :: get_instance()->retrieve_groups(new EqualityCondition(Group :: PROPERTY_PARENT, 0))->next_result();
            $this->root_group = $group;
        }
        
        return $this->root_group;
    }
}

?>