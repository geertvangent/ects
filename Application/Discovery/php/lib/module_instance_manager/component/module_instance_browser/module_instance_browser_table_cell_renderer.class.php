<?php
namespace application\discovery;

use common\libraries\EqualityCondition;
use common\libraries\Translation;
use common\libraries\Utilities;
use common\libraries\ToolbarItem;
use common\libraries\Toolbar;
use common\libraries\Theme;
use rights\RightsManager;

class ModuleInstanceBrowserTableCellRenderer extends DefaultModuleInstanceTableCellRenderer
{

    private $browser;

    /**
     * Constructor
     * 
     * @param ModuleInstanceManager $browser
     */
    public function __construct($browser)
    {
        parent :: __construct();
        $this->browser = $browser;
    }
    
    // Inherited
    public function render_cell($column, $module_instance)
    {
        if ($column === ModuleInstanceBrowserTableColumnModel :: get_modification_column())
        {
            return $this->get_modification_links($module_instance);
        }
        
        // switch ($column->get_name())
        // {
        // // case ContentObject :: PROPERTY_TYPE :
        // // return '<a href="' . htmlentities($this->browser->get_type_filter_url($external_repository->get_type())) .
        // '">' . parent :: render_cell($column, $external_repository) . '</a>';
        // // case ContentObject :: PROPERTY_TITLE :
        // // $title = parent :: render_cell($column, $external_repository);
        // // $title_short = Utilities :: truncate_string($title, 53, false);
        // // return '<a href="' . htmlentities($this->browser->get_content_object_viewing_url($external_repository)) .
        // '" title="' . $title . '">' . $title_short . '</a>';
        // }
        return parent :: render_cell($column, $module_instance);
    }

    /**
     * Gets the action links to display
     * 
     * @param ContentObject $content_object The learning object for which the action links should be returned
     * @return string A HTML representation of the action links
     */
    private function get_modification_links($module_instance)
    {
        $toolbar = new Toolbar();
        
        $allowed = $this->check_move_allowed($module_instance);
        
        if ($this->is_display_order_column())
        {
            if ($allowed["moveup"])
            {
                $toolbar->add_item(
                        new ToolbarItem(Translation :: get('MoveUp', null, Utilities :: COMMON_LIBRARIES), 
                                Theme :: get_common_image_path() . 'action_up.png', 
                                $this->browser->get_url(
                                        array(
                                                ModuleInstanceManager :: PARAM_INSTANCE_ACTION => ModuleInstanceManager :: ACTION_MOVE_INSTANCE, 
                                                DiscoveryManager :: PARAM_MODULE_ID => $module_instance->get_id(), 
                                                DiscoveryManager :: PARAM_DIRECTION => DiscoveryManager :: PARAM_DIRECTION_UP)), 
                                ToolbarItem :: DISPLAY_ICON));
            }
            else
            {
                $toolbar->add_item(
                        new ToolbarItem(Translation :: get('MoveUpNotAvailable', null, Utilities :: COMMON_LIBRARIES), 
                                Theme :: get_common_image_path() . 'action_up_na.png', null, ToolbarItem :: DISPLAY_ICON));
            }
            
            if ($allowed["movedown"])
            {
                $toolbar->add_item(
                        new ToolbarItem(Translation :: get('MoveDown', null, Utilities :: COMMON_LIBRARIES), 
                                Theme :: get_common_image_path() . 'action_down.png', 
                                $this->browser->get_url(
                                        array(
                                                ModuleInstanceManager :: PARAM_INSTANCE_ACTION => ModuleInstanceManager :: ACTION_MOVE_INSTANCE, 
                                                DiscoveryManager :: PARAM_MODULE_ID => $module_instance->get_id(), 
                                                DiscoveryManager :: PARAM_DIRECTION => DiscoveryManager :: PARAM_DIRECTION_DOWN)), 
                                ToolbarItem :: DISPLAY_ICON));
            }
            else
            {
                $toolbar->add_item(
                        new ToolbarItem(Translation :: get('MoveDownNotAvailable', null, Utilities :: COMMON_LIBRARIES), 
                                Theme :: get_common_image_path() . 'action_down_na.png', null, 
                                ToolbarItem :: DISPLAY_ICON));
            }
        }
        
        if ($module_instance->is_enabled())
        {
            $toolbar->add_item(
                    new ToolbarItem(Translation :: get('Deactivate', null, Utilities :: COMMON_LIBRARIES), 
                            Theme :: get_common_image_path() . 'action_deactivate.png', 
                            $this->browser->get_url(
                                    array(
                                            ModuleInstanceManager :: PARAM_INSTANCE_ACTION => ModuleInstanceManager :: ACTION_DEACTIVATE_INSTANCE, 
                                            DiscoveryManager :: PARAM_MODULE_ID => $module_instance->get_id())), 
                            ToolbarItem :: DISPLAY_ICON, true));
        }
        else
        {
            $toolbar->add_item(
                    new ToolbarItem(Translation :: get('Activate', null, Utilities :: COMMON_LIBRARIES), 
                            Theme :: get_common_image_path() . 'action_activate.png', 
                            $this->browser->get_url(
                                    array(
                                            ModuleInstanceManager :: PARAM_INSTANCE_ACTION => ModuleInstanceManager :: ACTION_ACTIVATE_INSTANCE, 
                                            DiscoveryManager :: PARAM_MODULE_ID => $module_instance->get_id())), 
                            ToolbarItem :: DISPLAY_ICON, true));
        }
        
        $toolbar->add_item(
                new ToolbarItem(Translation :: get('Edit', null, Utilities :: COMMON_LIBRARIES), 
                        Theme :: get_common_image_path() . 'action_edit.png', 
                        $this->browser->get_url(
                                array(
                                        ModuleInstanceManager :: PARAM_INSTANCE_ACTION => ModuleInstanceManager :: ACTION_UPDATE_INSTANCE, 
                                        DiscoveryManager :: PARAM_MODULE_ID => $module_instance->get_id())), 
                        ToolbarItem :: DISPLAY_ICON));
        $toolbar->add_item(
                new ToolbarItem(Translation :: get('Delete', null, Utilities :: COMMON_LIBRARIES), 
                        Theme :: get_common_image_path() . 'action_delete.png', 
                        $this->browser->get_url(
                                array(
                                        ModuleInstanceManager :: PARAM_INSTANCE_ACTION => ModuleInstanceManager :: ACTION_DELETE_INSTANCE, 
                                        DiscoveryManager :: PARAM_MODULE_ID => $module_instance->get_id())), 
                        ToolbarItem :: DISPLAY_ICON, true));
        $toolbar->add_item(
                new ToolbarItem(Translation :: get('ManageRights', null, RightsManager :: APPLICATION_NAME), 
                        Theme :: get_common_image_path() . 'action_rights.png', 
                        $this->browser->get_url(
                                array(
                                        ModuleInstanceManager :: PARAM_INSTANCE_ACTION => ModuleInstanceManager :: ACTION_MANAGE_INSTANCE_RIGHTS, 
                                        DiscoveryManager :: PARAM_MODULE_ID => $module_instance->get_id())), 
                        ToolbarItem :: DISPLAY_ICON));
        return $toolbar->as_html();
    }

    protected function check_move_allowed($module_instance)
    {
        $moveup_allowed = true;
        $movedown_allowed = true;
        
        $count = DataManager :: get_instance()->count_module_instances(
                new EqualityCondition(ModuleInstance :: PROPERTY_CONTENT_TYPE, $module_instance->get_content_type()));
        if ($count == 1)
        {
            $moveup_allowed = false;
            $movedown_allowed = false;
        }
        else
        {
            if ($module_instance->get_display_order() == 1)
            {
                $moveup_allowed = false;
            }
            else
            {
                if ($module_instance->get_display_order() == $count)
                {
                    $movedown_allowed = false;
                }
            }
        }
        
        return array('moveup' => $moveup_allowed, 'movedown' => $movedown_allowed);
    }
}
