<?php
namespace application\discovery\rights_editor_manager;

use common\libraries\AjaxManager;
use rights\RightsUtil;
use common\libraries\JsonAjaxResult;

/**
 *
 * @author Sven Vanpoucke
 * @package rights.ajax
 */
class WeblcmsAjaxEntityRightLocation extends AjaxManager
{
    const PARAM_LOCATIONS = 'locations';
    const PARAM_RIGHTS = 'rights';
    const PROPERTY_SUCCESS = 'success';

    function required_parameters()
    {
        return array(self :: PARAM_LOCATIONS, self :: PARAM_RIGHTS);
    }

    function run()
    {
        $locations = $this->get_parameter(self :: PARAM_LOCATIONS);
        $locations = json_decode($locations);
        
        $rights = $this->get_parameter(self :: PARAM_RIGHTS);
        $rights = explode('|', $rights);
        
        $context = $rights['1'];
        $right_id = $rights['2'];
        $entity_type = $rights['3'];
        $entity_item_id = $rights['4'];
        
        $rights_util = RightsUtil :: get_instance();
        
        if (isset($context) && isset($right_id) && isset($entity_type) && isset($entity_item_id) && isset($locations) && count(
                $locations) > 0)
        {
            $success = true;
            
            foreach ($locations as $location_id)
            {
                $success &= $rights_util->invert_location_entity_right($context, $right_id, $entity_item_id, 
                        $entity_type, $location_id);
            }
            
            $result = new JsonAjaxResult();
            $result->set_property(self :: PROPERTY_SUCCESS, $success);
            $result->display();
        }
        else
        {
            JsonAjaxResult :: bad_request();
        }
    }
}

?>