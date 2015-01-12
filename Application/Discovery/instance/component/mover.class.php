<?php
namespace Application\Discovery\instance\component;

use libraries\storage\AndCondition;
use libraries\platform\Request;
use libraries\utilities\Utilities;
use libraries\platform\translation\Translation;
use libraries\storage\EqualityCondition;
use libraries\storage\DataClassCountParameters;
use libraries\storage\DataClassRetrieveParameters;
use libraries\storage\StaticConditionVariable;
use libraries\storage\PropertyConditionVariable;

class MoverComponent extends Manager
{

    public function run()
    {
        $id = Request :: get(Manager :: PARAM_MODULE_ID);
        $direction = Request :: get(Manager :: PARAM_DIRECTION);
        $succes = true;
        
        if (isset($id))
        {
            $instance = DataManager :: retrieve_by_id(Instance :: class_name(), (int) $id);
            
            $max = DataManager :: count(
                Instance :: class_name(), 
                new DataClassCountParameters(
                    new EqualityCondition(
                        new PropertyConditionVariable(Instance :: class_name(), Instance :: PROPERTY_CONTENT_TYPE), 
                        new StaticConditionVariable($instance->get_content_type()))));
            
            $display_order = $instance->get_display_order();
            $new_place = ($display_order + ($direction == Manager :: PARAM_DIRECTION_UP ? - 1 : 1));
            
            if ($new_place > 0 && $new_place <= $max)
            {
                $instance->set_display_order($new_place);
                
                $conditions[] = new EqualityCondition(Instance :: PROPERTY_DISPLAY_ORDER, $new_place);
                $conditions[] = new EqualityCondition(Instance :: PROPERTY_CONTENT_TYPE, $instance->get_content_type());
                $condition = new AndCondition($conditions);
                
                $new_instance = DataManager :: retrieve(
                    Instance :: class_name(), 
                    new DataClassRetrieveParameters($condition));
                $new_instance->set_display_order($display_order);
                
                if (! $instance->update() || ! $new_instance->update())
                {
                    $succes = false;
                }
            }
            $this->redirect(
                Translation :: get(
                    $succes ? 'ObjectUpdated' : 'ObjectNotUpdated', 
                    array('OBJECT' => Translation :: get('Instance')), 
                    Utilities :: COMMON_LIBRARIES), 
                ($succes ? false : true), 
                array(
                    self :: PARAM_ACTION => self :: ACTION_BROWSE_INSTANCES, 
                    self :: PARAM_CONTENT_TYPE => $instance->get_content_type()));
        }
        else
        {
            $this->display_error_page(
                htmlentities(
                    Translation :: get(
                        'NoObjectsSelected', 
                        array('OBJECTS' => Translation :: get('ContentObjectItems')), 
                        Utilities :: COMMON_LIBRARIES)));
        }
    }
}
