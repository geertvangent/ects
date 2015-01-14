<?php
namespace Chamilo\Application\EhbSync\Bamaflex;

/**
 *
 * @author Hans De Bisschop
 */
class DataManager extends \Chamilo\Libraries\Storage\DataManager\DataManager
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
