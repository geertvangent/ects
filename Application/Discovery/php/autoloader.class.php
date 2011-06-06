<?php
namespace application\discovery;

use common\libraries\Utilities;
use common\libraries\WebApplication;

/**
 * @author Hans De Bisschop
 * @package application.discovery
 */
class Autoloader
{

    static function load($classname)
    {
        $list = array('discovery_data_manager' => 'discovery_data_manager',
                'discovery_data_manager_interface' => 'discovery_data_manager_interface',
                'discovery_manager' => 'discovery_manager/discovery_manager',
                'discovery_module_instance' => 'discovery_module_instance',
                'discovery_module_instance_setting' => 'discovery_module_instance_setting');

        $lower_case = Utilities :: camelcase_to_underscores($classname);

        if (key_exists($lower_case, $list))
        {
            $url = $list[$lower_case];
            require_once WebApplication :: get_application_class_lib_path('discovery') . $url . '.class.php';
            return true;
        }

        return false;
    }

}

?>