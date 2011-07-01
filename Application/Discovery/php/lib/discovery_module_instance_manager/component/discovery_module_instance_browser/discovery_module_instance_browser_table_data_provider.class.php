<?php
namespace application\discovery;

use common\libraries\ObjectTableDataProvider;

class DiscoveryModuleInstanceBrowserTableDataProvider extends ObjectTableDataProvider
{

    /**
     * Constructor
     * @param DiscoveryModuleInstanceManager $browser
     * @param Condition $condition
     */
    function __construct($browser, $condition)
    {
        parent :: __construct($browser, $condition);
    }

    /**
     * @param int $offset
     * @param int $count
     * @param string $order_property
     * @return ResultSet A set of matching external repositories.
     */
    function get_objects($offset, $count, $order_property = null)
    {
        $order_property = $this->get_order_property($order_property);
        return DiscoveryDataManager::get_instance()->retrieve_discovery_module_instances($this->get_condition(), $offset, $count, $order_property);
    }

    function get_object_count()
    {
        return DiscoveryDataManager::get_instance()->count_discovery_module_instances($this->get_condition());
    }
}
?>