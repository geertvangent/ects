<?php
namespace application\discovery\module\assessment_results\implementation\chamilo;

use libraries\DataClassRetrievesParameters;
use libraries\NewObjectTableDataProvider;

/**
 * $Id: user_browser_table_data_provider.class.php 211 2009-11-13 13:28:39Z vanpouckesven $
 * 
 * @package user.lib.user_manager.component.user_browser
 */
/**
 * Data provider for a user browser table. This class implements some functions to allow user browser tables to retrieve
 * information about the users to display.
 */
class UserBrowserTableDataProvider extends NewObjectTableDataProvider
{

    /**
     * Gets the users
     * 
     * @param String $user
     * @param String $category
     * @param int $offset
     * @param int $count
     * @param string $order_property
     * @return ResultSet A set of matching learning objects.
     */
    public function get_objects($offset, $count, $order_property = null)
    {
        $order_property = $this->get_order_property($order_property);
        
        $parameters = new DataClassRetrievesParameters($this->get_condition(), $count, $offset, $order_property);
        return \core\user\DataManager :: retrieves(\core\user\User :: class_name(), $parameters);
    }

    /**
     * Gets the number of users in the table
     * 
     * @return int
     */
    public function get_object_count()
    {
        return \core\user\DataManager :: count(\core\user\User :: class_name(), $this->get_condition());
    }
}
