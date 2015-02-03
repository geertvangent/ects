<?php
namespace Ehb\Application\Sync\Cas\Synchronization;

use Chamilo\Libraries\Utilities\StringUtilities;

abstract class Synchronization
{

    /**
     *
     * @param $type string
     * @return Synchronization
     */
    public static function factory($type)
    {
        $class = __NAMESPACE__ . '\\' . StringUtilities :: getInstance()->createString($type)->upperCamelize() .
             'Synchronization';
        if (class_exists($class))
        {
            return new $class();
        }
    }

    abstract public function run();

    /**
     *
     * @param $type string
     * @param $message string
     */
    public static function log($type, $message)
    {
        echo '[' . str_pad(strtoupper($type), 18, ' ', STR_PAD_LEFT) . '] ' . $message . "\n";
    }
}
