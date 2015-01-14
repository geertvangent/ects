<?php
namespace Application\EhbSync\data\storage\data_manager;

/**
 *
 * @author Hans De Bisschop
 */
class DataManager extends \libraries\storage\data_manager\DataManager
{
    const PREFIX = 'ehb_sync_data';

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
