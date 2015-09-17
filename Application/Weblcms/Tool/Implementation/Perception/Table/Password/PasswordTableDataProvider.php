<?php
namespace Ehb\Application\Weblcms\Tool\Implementation\Perception;

use Chamilo\Libraries\Format\Table\Extension\DataClassTable\DataClassTableDataProvider;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;
use Chamilo\Libraries\Storage\Parameters\DataClassCountParameters;

class PasswordTableDataProvider extends DataClassTableDataProvider
{

    /**
     * Gets the users
     *
     * @param $user String
     * @param $category String
     * @param $offset int
     * @param $count int
     * @param $order_property string
     * @return ResultSet A set of matching learning objects.
     */
    public function retrieve_data($offset, $count, $order_property = null)
    {
        $parameters = new DataClassRetrievesParameters($this->get_condition(), $count, $offset, $order_property);
        return DataManager :: retrieves(Password :: class_name(), $parameters);
    }

    /**
     * Gets the number of users in the table
     *
     * @return int
     */
    public function count_data()
    {
        $parameters = new DataClassCountParameters($this->get_condition());
        return DataManager :: count(Password :: class_name(), $parameters);
    }
}
