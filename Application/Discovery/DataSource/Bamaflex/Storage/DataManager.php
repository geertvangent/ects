<?php
namespace Ehb\Application\Discovery\DataSource\Bamaflex\Storage;

/**
 *
 * @package application.discovery
 * @author Hans De Bisschop
 */
class DataManager extends \Chamilo\Libraries\Storage\DataManager\DataManager
{
    const PREFIX = 'discovery_bamaflex_';

    public static function get_type()
    {
        return 'doctrine';
    }
}
