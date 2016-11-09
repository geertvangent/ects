<?php
namespace Ehb\Application\Discovery\Storage;

/**
 *
 * @package application.discovery
 * @author Hans De Bisschop
 */
class DataManager extends \Chamilo\Libraries\Storage\DataManager\DataManager
{
    const PREFIX = 'discovery_';

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
