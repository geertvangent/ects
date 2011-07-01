<?php
namespace application\discovery\module\enrollment;

use common\libraries\Utilities;

/**
 * @author Hans De Bisschop
 * @package application.discovery
 */
class Autoloader
{

    static function load($classname)
    {
        $list = array('data_manager' => 'data_manager', 'data_manager_interface' => 'data_manager_interface',
                'enrollment' => 'enrollment', 'module' => 'module');

        $lower_case = Utilities :: camelcase_to_underscores($classname);

        if (key_exists($lower_case, $list))
        {
            $url = $list[$lower_case];
            require_once dirname(__FILE__) . '/lib/' . $url . '.class.php';
            return true;
        }

        return false;
    }

}

?>