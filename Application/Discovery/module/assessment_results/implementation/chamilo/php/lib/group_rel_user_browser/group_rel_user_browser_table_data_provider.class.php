<?php
namespace application\discovery\module\assessment_results\implementation\chamilo;

use libraries\ObjectTableDataProvider;

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
        return \core\group\DataManager :: get_instance()->retrieve_group_rel_users(
            $this->get_condition(),
            $offset,
            $count,
            $order_property);
    }

    /**
     * Gets the number of learning objects in the table
     *
     * @return int
     */
    public function get_object_count()
    {
        return \core\group\DataManager :: get_instance()->count_group_rel_users($this->get_condition());
    }
}
