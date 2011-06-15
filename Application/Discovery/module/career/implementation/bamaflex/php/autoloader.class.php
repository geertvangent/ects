<?php
namespace application\discovery\module\career\implementation\bamaflex;

use common\libraries\Utilities;

/**
 * @author Hans De Bisschop
 * @package application.discovery
 */
class Autoloader
{

    static function load($classname)
    {
        $list = array('course' => 'course', 'module' => 'module',
                'settings_connector' => '../settings/settings_connector');

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