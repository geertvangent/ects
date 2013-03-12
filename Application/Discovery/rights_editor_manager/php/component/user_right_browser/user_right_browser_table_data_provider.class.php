<?php
namespace application\discovery\rights_editor_manager;

use user\UserDataManager;
use common\libraries\ObjectTableDataProvider;

/**
 *
 * @author Sven Vanpoucke
 * @package application.common.rights_editor_manager.component.location_group_bowser
 */

/**
 * Data provider for the entity browser table. Retrieves data with use of the selected entity
 */
class UserRightBrowserTableDataProvider extends ObjectTableDataProvider
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

        return UserDataManager :: get_instance()->retrieve_users($this->get_condition(), $offset, $count,
                $order_property);
    }

    public function get_object_count()
    {
        return UserDataManager :: get_instance()->count_users($this->get_condition());
    }
}
