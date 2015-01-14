<?php
namespace Application\EhbSync\cas\synchronization;

use libraries\utilities\Utilities;

abstract class Synchronization
{

    /**
     *
     * @param $type string
     * @return Synchronization
     */
    public static function factory($type)
    {
        $class = __NAMESPACE__ . '\\' . Utilities :: underscores_to_camelcase($type) . 'Synchronization';
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
