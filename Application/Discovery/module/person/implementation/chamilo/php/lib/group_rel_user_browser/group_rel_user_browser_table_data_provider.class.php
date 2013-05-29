<?php
namespace application\discovery\module\person\implementation\chamilo;

use common\libraries\ObjectTableDataProvider;
use common\libraries\DataClassRetrievesParameters;
use common\libraries\DataClassCountParameters;

/**
 * $Id: group_rel_user_browser_table_data_provider.class.php 224 2009-11-13 14:40:30Z kariboe $
 * 
 * @package groups.lib.group_manager.component.group_rel_user_browser
 */
/**
 * Data provider for a repository browser table. This class implements some functions to allow repository browser tables
 * to retrieve information about the learning objects to display.
 */
class GroupRelUserBrowserTableDataProvider extends ObjectTableDataProvider
{

    /**
     * Constructor
     * 
     * @param RepositoryManagerComponent $browser
     * @param Condition $condition
     */
    public function __construct($browser, $condition)
    {
        parent :: __construct($browser, $condition);
    }

    /**
     * Gets the learning objects
     * 
     * @param int $offset
     * @param int $count
     * @param string $order_property
     * @return ResultSet A set of matching learning objects.
     */
    public function get_objects($offset, $count, $order_property = null)
    {
        $order_property = $this->get_order_property($order_property);
        return \group\DataManager :: retrieves(
            \group\GroupRelUser :: class_name(), 
            new DataClassRetrievesParameters($this->get_condition(), $count, $offset, $order_property));
    }

    /**
     * Gets the number of learning objects in the table
     * 
     * @return int
     */
    public function get_object_count()
    {
        return \group\DataManager :: count(
            \group\GroupRelUser :: class_name(), 
            new DataClassCountParameters($this->get_condition()));
    }
}
