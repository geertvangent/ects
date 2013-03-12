<?php
namespace application\discovery;

use common\libraries\ObjectTableDataProvider;

class ModuleInstanceBrowserTableDataProvider extends ObjectTableDataProvider
{

    /**
     * Constructor
     *
     * @param ModuleInstanceManager $browser
     * @param Condition $condition
     */
    public function __construct($browser, $condition)
    {
        parent :: __construct($browser, $condition);
    }

    /**
     *
     * @param int $offset
     * @param int $count
     * @param string $order_property
     * @return ResultSet A set of matching external repositories.
     */
    public function get_objects($offset, $count, $order_property = null)
    {
        $order_property = $this->get_order_property($order_property);
        return DataManager :: get_instance()->retrieve_module_instances($this->get_condition(), $offset,
                $count, $order_property);
    }

    public function get_object_count()
    {
        return DataManager :: get_instance()->count_module_instances($this->get_condition());
    }
}
