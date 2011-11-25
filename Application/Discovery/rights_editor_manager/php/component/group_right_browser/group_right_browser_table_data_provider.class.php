<?php
namespace application\discovery\rights_editor_manager;

use common\libraries\ObjectTableDataProvider;

/**
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
     * @param Application $browser
     * @param Condition $condition
     */
    function __construct($browser, $condition)
    {
        parent :: __construct($browser, $condition);
    }

    function get_objects($offset, $count, $order_property = null)
    {
        $order_property = $this->get_order_property($order_property);
        
        $selected_entity = $this->get_browser()->get_selected_entity();
        return $selected_entity->retrieve_entity_items($this->get_condition(), $offset, $count, $order_property);
    }

    function get_object_count()
    {
        $selected_entity = $this->get_browser()->get_selected_entity();
        return $selected_entity->count_entity_items($this->get_condition());
    }
}
?>