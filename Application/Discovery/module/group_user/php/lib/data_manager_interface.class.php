<?php
namespace application\discovery\module\group_user;

/**
 * @package application.discovery
 * @author Hans De Bisschop
 */
interface DataManagerInterface
{

    /**
     * @param Parameters $group_user_parameters
     * @return multitype:\application\discovery\module\group_user\GroupUser
     */
    function retrieve_group_users($group_user_parameters);
}
?>