<?php
namespace application\atlantis\role\entity;

use common\libraries\AdvancedElementFinderElement;
use user\UserAjaxUsersFeed;
use common\libraries\Request;
use common\libraries\Utilities;
use user\User;
use common\libraries\AndCondition;
use common\libraries\DataClassRetrievesParameters;
use common\libraries\ObjectTableOrder;
use common\libraries\InCondition;
use common\libraries\EqualityCondition;
use common\libraries\DataClassCountParameters;

/**
 * Feed to return users from the user entity
 *
 * @package rights
 * @author Sven Vanpoucke - Hogeschool Gent
 */
class EntityAjaxUserEntityFeed extends UserAjaxUsersFeed
{

    /**
     * Returns the advanced element finder element for the given user
     *
     * @param User $user
     *
     * @return AdvancedElementFinderElement
     */
    protected function get_element_for_user($user)
    {
        return new AdvancedElementFinderElement(
            UserEntity :: ENTITY_TYPE . '_' . $user->get_id(),
            'type type_user',
            $user->get_fullname(),
            $user->get_official_code());
    }

    /**
     * Retrieves the users from the course (direct subscribed and group subscribed)
     *
     * @return ResultSet
     */
    public function retrieve_users()
    {
        $search_query = Request :: post(self :: PARAM_SEARCH_QUERY);

        // Set the conditions for the search query
        if ($search_query && $search_query != '')
        {
            $conditions[] = Utilities :: query_to_condition(
                $search_query,
                array(User :: PROPERTY_USERNAME, User :: PROPERTY_FIRSTNAME, User :: PROPERTY_LASTNAME));
        }

        if (! $this->get_user()->is_platform_admin() &&
             \application\atlantis\rights\Rights :: get_instance()->access_is_allowed())
        {
            $target_users = \application\atlantis\rights\Rights :: get_instance()->get_target_users($this->get_user());

            if (count($target_users) > 0)
            {
                $conditions[] = new InCondition(User :: PROPERTY_ID, $target_users);
            }
            else
            {
                $conditions[] = new EqualityCondition(User :: PROPERTY_ID, - 1);
            }
        }

        // Combine the conditions
        $count = count($conditions);
        if ($count > 1)
        {
            $condition = new AndCondition($conditions);
        }

        if ($count == 1)
        {
            $condition = $conditions[0];
        }

        $this->set_user_count(\user\DataManager :: count(User :: class_name(), new DataClassCountParameters($condition)));
        $parameters = new DataClassRetrievesParameters(
            $condition,
            100,
            $this->get_offset(),
            array(new ObjectTableOrder(User :: PROPERTY_FIRSTNAME), new ObjectTableOrder(User :: PROPERTY_LASTNAME)));

        return \user\DataManager :: retrieves(User :: class_name(), $parameters);
    }
}
