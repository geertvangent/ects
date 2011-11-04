<?php
namespace application\discovery\module\person\implementation\chamilo;
use application\discovery\module\person\DataManager;

use user\UserDataManager;

use common\libraries\ObjectTableDataProvider;
/**
 * $Id: user_browser_table_data_provider.class.php 211 2009-11-13 13:28:39Z vanpouckesven $
 * @package user.lib.user_manager.component.user_browser
 */
/**
 * Data provider for a user browser table.
 *
 * This class implements some functions to allow user browser tables to
 * retrieve information about the users to display.
 */
class UserBrowserTableDataProvider extends ObjectTableDataProvider
{

    /**
     * Constructor
     * @param UserManagerComponent $browser
     * @param Condition $condition
     */
    function __construct($browser, $condition)
    {
        parent :: __construct($browser, $condition);
    }

    /**
     * Gets the users
     * @param String $user
     * @param String $category
     * @param int $offset
     * @param int $count
     * @param string $order_property
     * @return ResultSet A set of matching learning objects.
     */
    function get_objects($offset, $count, $order_property = null)
    {
        $order_property = $this->get_order_property($order_property);
        return DataManager:: get_instance($this->get_browser()->get_module_instance())->retrieve_persons($this->get_condition(), $offset, $count, $order_property);
    }

    /**
     * Gets the number of users in the table
     * @return int
     */
    function get_object_count()
    {
        return DataManager:: get_instance($this->get_browser()->get_module_instance())->count_persons($this->get_condition());
    	    }
}
?>