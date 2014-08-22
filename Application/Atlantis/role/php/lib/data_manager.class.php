<?php
namespace application\atlantis\role;

class DataManager extends \libraries\DataManager
{
    const PREFIX = "atlantis_";
    /**
     * Gets the type of DataManager to be instantiated
     *
     * @return string
     */
    public static function get_type()
    {
        return 'doctrine';
    }
}
