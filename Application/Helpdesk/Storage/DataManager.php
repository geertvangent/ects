<?php
namespace Ehb\Application\Helpdesk\Storage;

/**
 *
 * @author Hans De Bisschop
 */
class DataManager extends \Chamilo\Libraries\Storage\DataManager\DataManager
{
    const PREFIX = 'helpdesk_';

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
