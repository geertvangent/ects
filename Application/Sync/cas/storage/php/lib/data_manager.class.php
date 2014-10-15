<?php
namespace application\ehb_sync\cas\storage;

/**
 *
 * @author Hans De Bisschop
 */
class DataManager extends \libraries\storage\DataManager
{

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
