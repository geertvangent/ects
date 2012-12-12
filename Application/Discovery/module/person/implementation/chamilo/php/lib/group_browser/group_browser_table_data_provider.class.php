<?php
namespace application\discovery\module\person\implementation\chamilo;

use group\GroupDataManager;
use common\libraries\ObjectTableDataProvider;

/**
 * $Id: group_browser_table_data_provider.class.php 224 2009-11-13 14:40:30Z kariboe $
 * 
 * @package groups.lib.group_manager.component.group_browser
 */
/**
 * Data provider for a repository browser table. This class implements some functions to allow repository browser tables
 * to retrieve information about the learning objects to display.
 */
class GroupBrowserTableDataProvider extends ObjectTableDataProvider
{

    /**
     * Constructor
     * 
     * @param RepositoryManagerComponent $browser
     * @param Condition $condition
     */
    function __construct($browser, $condition)
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
    function get_objects($offset, $count, $order_property = null)
    {
        $order_property = $this->get_order_property($order_property);
        return GroupDataManager :: get_instance()->retrieve_groups($this->get_condition(), $offset, $count, 
                $order_property);
    }

    /**
     * Gets the number of learning objects in the table
     * 
     * @return int
     */
    function get_object_count()
    {
        return GroupDataManager :: get_instance()->count_groups($this->get_condition());
    }
}
?>