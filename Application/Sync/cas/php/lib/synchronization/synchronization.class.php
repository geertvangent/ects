<?php
namespace application\ehb_sync\cas;

use common\libraries\PlatformSetting;

use common\libraries\Mdb2ResultSet;
use common\libraries\Utilities;

abstract class Synchronization
{

    /**
     *
     * @param $type string           
     * @return Synchronization
     */
    static function factory($type)
    {
        $class = __NAMESPACE__ . '\\' . Utilities :: underscores_to_camelcase($type) . 'Synchronization';
        if (class_exists($class))
        {
            return new $class();
        }
    }

    abstract function run();

    /**
     *
     * @param $type string           
     * @param $message string           
     */
    static function log($type, $message)
    {
        echo '[' . str_pad(strtoupper($type), 18, ' ', STR_PAD_LEFT) . '] ' . $message . "\n";
    }
}