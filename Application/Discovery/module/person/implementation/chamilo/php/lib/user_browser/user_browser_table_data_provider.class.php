<?php
namespace application\discovery\module\person\implementation\chamilo;
use common\libraries\EqualityCondition;

use common\libraries\OrCondition;

use user\User;

use group\GroupDataManager;

use common\libraries\AndCondition;

use group\GroupRelUser;

use common\libraries\InCondition;

use application\discovery\module\person\DataManager;

use user\UserDataManager;

use common\libraries\ObjectTableDataProvider;
/**
 * $Id: user_browser_table_data_provider.class.php 211 2009-11-13 13:28:39Z vanpouckesven $
 * @package user.lib.user_manager.component.user_browser
 */
/**
 * Data provider for a user browser table.
 *
 * This class implements some functions to allow user browser tables to
 * retrieve information about the users to display.
 */
class UserBrowserTableDataProvider extends ObjectTableDataProvider
{

    /**
     * Constructor
     * @param UserManagerComponent $browser
     * @param Condition $condition
     */
    function __construct($browser, $condition)
    {
        parent :: __construct($browser, $condition);
    }

    /**
     * Gets the users
     * @param String $user
     * @param String $category
     * @param int $offset
     * @param int $count
     * @param string $order_property
     * @return ResultSet A set of matching learning objects.
     */
    function get_objects($offset, $count, $order_property = null)
    {
        $order_property = $this->get_order_property($order_property);
        
        if ($this->get_browser()->get_application()->get_user()->is_platform_admin())
        {
            return UserDataManager :: get_instance()->retrieve_users($this->get_condition(), $offset, $count, $order_property);
        }
        else
        {
            $conditions = array();
            if ($this->get_condition())
            {
                $conditions[] = $this->get_condition();
            }
            
            $group_data_manager = GroupDataManager :: get_instance();
            $group_rel_users = $group_data_manager->retrieve_user_groups($this->get_browser()->get_application()->get_user()->get_id());
            $user_conditions = array();
            
            while ($group_rel_user = $group_rel_users->next_result())
            {
                $users = $group_data_manager->retrieve_group($group_rel_user->get_group_id())->get_users(true, true);
                $user_conditions[] = new InCondition(User :: PROPERTY_ID, $users);
            }
            $user_conditions[] = new EqualityCondition(User :: PROPERTY_ID, 0);
            $conditions[] = new OrCondition($user_conditions);
            $condition = new AndCondition($conditions);
            
            return UserDataManager :: get_instance()->retrieve_users($condition, $offset, $count, $order_property);
        }
    }

    /**
     * Gets the number of users in the table
     * @return int
     */
    function get_object_count()
    {
        if ($this->get_browser()->get_application()->get_user()->is_platform_admin())
        {
            return UserDataManager :: get_instance()->count_users($this->get_condition());
        }
        else
        {
            $conditions = array();
            if ($this->get_condition())
            {
                $conditions[] = $this->get_condition();
            }
            
            $group_data_manager = GroupDataManager :: get_instance();
            $group_rel_users = $group_data_manager->retrieve_user_groups($this->get_browser()->get_application()->get_user()->get_id());
            $user_conditions = array();
            
            while ($group_rel_user = $group_rel_users->next_result())
            {
                $users = $group_data_manager->retrieve_group($group_rel_user->get_group_id())->get_users(true, true);
                $user_conditions[] = new InCondition(User :: PROPERTY_ID, $users);
            }
            $user_conditions[] = new EqualityCondition(User :: PROPERTY_ID, 0);
            $conditions[] = new OrCondition($user_conditions);
            $condition = new AndCondition($conditions);
            
            return UserDataManager :: get_instance()->count_users($condition);
        }
    }
}
?>