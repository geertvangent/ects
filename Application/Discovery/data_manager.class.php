<?php
namespace Application\Discovery;

/**
 *
 * @package application.discovery
 * @author Hans De Bisschop
 */
class DataManager extends \libraries\storage\data_manager\DataManager
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
