<?php
namespace application\ehb_sync;

/**
 *
 * @author Hans De Bisschop
 */
class DataManager extends \libraries\DataManager
{
    const PREFIX = 'ehb_sync_';
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
