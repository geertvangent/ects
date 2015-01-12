<?php
namespace application\discovery\module\person\implementation\chamilo;

use libraries\format\TableDataProvider;
use libraries\storage\DataClassRetrievesParameters;
use libraries\storage\DataClassCountParameters;

/**
 * $Id: group_browser_table_data_provider.class.php 224 2009-11-13 14:40:30Z kariboe $
 *
 * @package groups.lib.group_manager.component.group_browser
 */
/**
 * Data provider for a repository browser table. This class implements some functions to allow repository browser tables
 * to retrieve information about the learning objects to display.
 */
class GroupBrowserTableDataProvider extends TableDataProvider
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

    public function retrieve_data($condition, $offset, $count, $order_property = null)
    {
        $order_property = $this->get_order_property($order_property);
        return \core\group\DataManager :: retrieves(
            \core\group\Group :: class_name(),
            new DataClassRetrievesParameters($this->get_condition(), $count, $offset, $order_property));
    }

    public function count_data($condition)
    {
        return \core\group\DataManager :: count(
            \core\group\Group :: class_name(),
            new DataClassCountParameters($this->get_condition()));
    }
}
