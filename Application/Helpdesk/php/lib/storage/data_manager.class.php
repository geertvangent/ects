<?php
namespace application\ehb_helpdesk;

/**
 *
 * @author Hans De Bisschop
 */
class DataManager extends \libraries\DataManager
{
const PREFIX = 'ehb_helpdesk_';
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
