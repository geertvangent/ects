<?php
namespace application\discovery\module\assessment_results\implementation\chamilo;

use libraries\TableDataProvider;

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

    /*
     * (non-PHPdoc) @see \libraries\TableDataProvider::retrieve_data()
     */
    public function retrieve_data($condition, $offset, $count, $order_property = null)
    {
        $order_property = $this->get_order_property($order_property);
        return \core\group\DataManager :: get_instance()->retrieve_groups(
            $this->get_condition(),
            $offset,
            $count,
            $order_property);
    }

    /*
     * (non-PHPdoc) @see \libraries\TableDataProvider::count_data()
     */
    public function count_data($condition)
    {
        return \core\group\DataManager :: get_instance()->count_groups($this->get_condition());
    }
}
