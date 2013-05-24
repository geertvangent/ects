<?php
namespace application\discovery\rights_editor_manager;

use group\GroupDataManager;
use common\libraries\ObjectTableDataProvider;

/**
 *
 * @author Sven Vanpoucke
 * @package application.common.rights_editor_manager.component.location_group_bowser
 */

/**
 * Data provider for the entity browser table. Retrieves data with use of the selected entity
 */
class GroupRightBrowserTableDataProvider extends ObjectTableDataProvider
{

    /**
     * Constructor
     * 
     * @param Application $browser
     * @param Condition $condition
     */
    public function __construct($browser, $condition)
    {
        parent :: __construct($browser, $condition);
    }

    public function get_objects($offset, $count, $order_property = null)
    {
        $order_property = $this->get_order_property($order_property);
        
        return GroupDataManager :: get_instance()->retrieve_groups(
            $this->get_condition(), 
            $offset, 
            $count, 
            $order_property);
    }

    public function get_object_count()
    {
        return GroupDataManager :: get_instance()->count_groups($this->get_condition());
    }
}
