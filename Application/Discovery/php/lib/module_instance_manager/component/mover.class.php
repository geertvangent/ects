<?php
namespace application\discovery;

use common\libraries\AndCondition;

use common\libraries\Request;

use common\libraries\Utilities;

use common\libraries\Translation;

use common\libraries\EqualityCondition;

class ModuleInstanceManagerMoverComponent extends ModuleInstanceManager
{

    function run()
    {
        $id = Request :: get(DiscoveryManager :: PARAM_MODULE_ID);
        $direction = Request :: get(DiscoveryManager :: PARAM_DIRECTION);
        $succes = true;
        
        if (isset($id))
        {
            $module_instance = DiscoveryDataManager :: get_instance()->retrieve_module_instance($id);
            
            $max = DiscoveryDataManager :: get_instance()->count_module_instances(new EqualityCondition(ModuleInstance :: PROPERTY_CONTENT_TYPE, $module_instance->get_content_type()));
            
            $display_order = $module_instance->get_display_order();
            $new_place = ($display_order + ($direction == DiscoveryManager :: PARAM_DIRECTION_UP ? - 1 : 1));
            
            if ($new_place > 0 && $new_place <= $max)
            {
                $module_instance->set_display_order($new_place);
                //                dump($module_instance->get_display_order());
                

                $conditions[] = new EqualityCondition(ModuleInstance :: PROPERTY_DISPLAY_ORDER, $new_place);
                $conditions[] = new EqualityCondition(ModuleInstance :: PROPERTY_CONTENT_TYPE, $module_instance->get_content_type());
                $condition = new AndCondition($conditions);
                
                $items = DiscoveryDataManager :: get_instance()->retrieve_module_instances($condition);
                $new_module_instance = $items->next_result();
                $new_module_instance->set_display_order($display_order);
                //                dump($new_module_instance->get_display_order());
                if (! $module_instance->update() || ! $new_module_instance->update())
                {
                    $succes = false;
                }
            }
            $this->redirect(Translation :: get($succes ? 'ObjectUpdated' : 'ObjectNotUpdated', array(
                    'OBJECT' => Translation :: get('ModuleInstance')), Utilities :: COMMON_LIBRARIES), ($succes ? false : true), array(
                    ModuleInstanceManager :: PARAM_INSTANCE_ACTION => ModuleInstanceManager :: ACTION_BROWSE_INSTANCES, ModuleInstanceManager::PARAM_CONTENT_TYPE => $module_instance->get_content_type()));
        }
        else
        {
            $this->display_error_page(htmlentities(Translation :: get('NoObjectsSelected', array(
                    'OBJECTS' => Translation :: get('ContentObjectItems')), Utilities :: COMMON_LIBRARIES)));
        }
    }
}
?>