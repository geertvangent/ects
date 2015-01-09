<?php
namespace Chamilo\Application\Atlantis\role\entitlement\storage;

/**
 * $Id: menu_data_manager.class.php 157 2009-11-10 13:44:02Z vanpouckesven $
 * 
 * @package menu.lib
 */
/**
 * This is a skeleton for a data manager for the Users table. Data managers must extend this class and implement its
 * abstract methods.
 * 
 * @author Hans De Bisschop
 * @author Dieter De Neef
 */
class DataManager extends \libraries\storage\data_manager\DataManager
{
    const PREFIX = "atlantis_";

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
