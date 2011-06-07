<?php
namespace application\discovery\module\profile\implementation\bamaflex;

use common\libraries\Utilities;

/**
 * @author Hans De Bisschop
 * @package application.discovery
 */
class Autoloader
{

    static function load($classname)
    {
        $list = array('address' => 'address', 'birth' => 'birth', 'profile' => 'profile');

        $lower_case = Utilities :: camelcase_to_underscores($classname);

        if (key_exists($lower_case, $list))
        {
            $url = $list[$lower_case];
            require_once dirname(__FILE__) . '/php/' . $url . '.class.php';
            return true;
        }

        return false;
    }

}

?>