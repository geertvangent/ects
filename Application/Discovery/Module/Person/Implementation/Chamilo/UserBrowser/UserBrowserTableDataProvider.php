<?php
namespace Ehb\Application\Discovery\Module\Person\Implementation\Chamilo\UserBrowser;

use Chamilo\Libraries\Format\Table\TableDataProvider;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;

/**
 * $Id: user_browser_table_data_provider.class.php 211 2009-11-13 13:28:39Z vanpouckesven $
 *
 * @package user.lib.user_manager.component.user_browser
 */
/**
 * Data provider for a user browser table. This class implements some functions to allow user browser tables to retrieve
 * information about the users to display.
 */
class UserBrowserTableDataProvider extends TableDataProvider
{

    public function retrieve_data($condition, $offset, $count, $order_property = null)
    {
        $order_property = $this->get_order_property($order_property);

        $parameters = new DataClassRetrievesParameters($this->get_condition(), $count, $offset, $order_property);
        return \Chamilo\Core\User\Storage\DataManager :: retrieves(
            \Chamilo\Core\User\Storage\DataClass\User :: class_name(),
            $parameters);
    }

    /**
     * Gets the number of users in the table
     *
     * @return int
     */
    public function count_data($condition)
    {
        return \Chamilo\Core\User\Storage\DataManager :: count(
            \Chamilo\Core\User\Storage\DataClass\User :: class_name(),
            $this->get_condition());
    }
}
