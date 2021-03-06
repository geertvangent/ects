<?php
namespace Ehb\Application\Sync\Cas\Storage;

/**
 *
 * @author Hans De Bisschop
 */
class DataManager extends \Chamilo\Libraries\Storage\DataManager\DataManager
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
