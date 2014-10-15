<?php
namespace application\atlantis\application\right;

class DataManager extends \libraries\storage\DataManager
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
