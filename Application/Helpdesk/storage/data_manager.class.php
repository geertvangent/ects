<?php
namespace Application\EhbHelpdesk\storage;

/**
 *
 * @author Hans De Bisschop
 */
class DataManager extends \libraries\storage\data_manager\DataManager
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
