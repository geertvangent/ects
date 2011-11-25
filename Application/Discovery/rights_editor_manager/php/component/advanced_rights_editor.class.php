<?php
namespace application\discovery\rights_editor_manager;

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
    	
    	$url = $this->get_url(array(GroupManager::PARAM_GROUP_ID => '%s'));
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
    	$table = new GroupRightBrowserTable($this, $this->get_parameters(), $this->get_condition());
    	return $table->as_html();
    }
    
    function get_condition()
    {
    	$group_conditions = array();
    	$group_conditions[] = new EqualityCondition(RightsGroupEntityRight::PROPERTY_MODULE_ID, Request :: get(DiscoveryManager::PARAM_MODULE_ID));
    	$group_conditions[] = new EqualityCondition(RightsGroupEntityRight::PROPERTY_GROUP_ID, Request :: get(GroupManager::PARAM_GROUP_ID));
		$group_condition = new AndCondition($group_conditions);
		
		$group_ids = DiscoveryDataManager::get_instance()->retrieve_distinct(RightsGroupEntityRight::get_table_name(), RightsGroupEntityRight::PROPERTY_ENTITY_ID, $group_condition);
    	return new InCondition(Group :: PROPERTY_ID, $group_ids);
    }
    
    function get_group()
    {
        if (! $this->group)
        {
            $this->group = Request::get(GroupManager::PARAM_GROUP_ID);
            
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
            $group = GroupDataManager::get_instance()->retrieve_groups(new EqualityCondition(Group::PROPERTY_PARENT, 0))->next_result();
            $this->root_group = $group;
        }
        
        return $this->root_group;
    }
}

?>