<?php
namespace Chamilo\Application\Discovery\Module\Person\Implementation\Chamilo\GroupRelUserBrowser;

use Chamilo\Libraries\Format\Table\TableDataProvider;
use Chamilo\Libraries\Storage\Parameters\DataClassCountParameters;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;

/**
 * $Id: group_rel_user_browser_table_data_provider.class.php 224 2009-11-13 14:40:30Z kariboe $
 *
 * @package groups.lib.group_manager.component.group_rel_user_browser
 */
/**
 * Data provider for a repository browser table. This class implements some functions to allow repository browser tables
 * to retrieve information about the learning objects to display.
 */
class GroupRelUserBrowserTableDataProvider extends TableDataProvider
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
     * (non-PHPdoc) @see \libraries\format\TableDataProvider::retrieve_data()
     */
    public function retrieve_data($condition, $offset, $count, $order_property = null)
    {
        $order_property = $this->get_order_property($order_property);
        return \Chamilo\Core\Group\Storage\DataManager :: retrieves(
            \Chamilo\Core\Group\Storage\DataClass\GroupRelUser :: class_name(),
            new DataClassRetrievesParameters($this->get_condition(), $count, $offset, $order_property));
    }

    /*
     * (non-PHPdoc) @see \libraries\format\TableDataProvider::count_data()
     */
    public function count_data($condition)
    {
        return \Chamilo\Core\Group\Storage\DataManager :: count(
            \Chamilo\Core\Group\Storage\DataClass\GroupRelUser :: class_name(),
            new DataClassCountParameters($this->get_condition()));
    }
}
