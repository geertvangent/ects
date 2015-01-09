<?php
namespace Chamilo\Application\Atlantis\Role\Entity\Ajax;

use Chamilo\Libraries\Format\AdvancedElementFinderElement;
use Chamilo\Libraries\Platform\Request;
use Chamilo\Libraries\Utilities\Utilities;
use Chamilo\Libraries\Storage\AndCondition;
use Chamilo\Libraries\Storage\DataClassRetrievesParameters;
use Chamilo\Libraries\Storage\StaticConditionVariable;
use Chamilo\Libraries\Storage\InCondition;
use Chamilo\Libraries\Storage\EqualityCondition;
use Chamilo\Libraries\Storage\DataClassCountParameters;
use Chamilo\Core\User\User;
use Chamilo\Libraries\Storage\PropertyConditionVariable;
use Chamilo\Libraries\Storage\OrderBy;

/**
 * Feed to return users from the user entity
 *
 * @package rights
 * @author Sven Vanpoucke - Hogeschool Gent
 */
class AjaxUserEntityFeed extends \Chamilo\Core\User\AjaxUsersFeed
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
             \Chamilo\Application\Atlantis\Rights\Rights :: get_instance()->access_is_allowed())
        {
            $target_users = \Chamilo\Application\Atlantis\Rights\Rights :: get_instance()->get_target_users($this->get_user());

            if (count($target_users) > 0)
            {
                $conditions[] = new InCondition(
                    new PropertyConditionVariable(User :: class_name(), User :: PROPERTY_ID),
                    $target_users);
            }
            else
            {
                $conditions[] = new EqualityCondition(
                    new PropertyConditionVariable(User :: class_name(), User :: PROPERTY_ID),
                    new StaticConditionVariable(- 1));
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

        $this->set_user_count(
            \Chamilo\Core\User\DataManager :: count(User :: class_name(), new DataClassCountParameters($condition)));
        $parameters = new DataClassRetrievesParameters(
            $condition,
            100,
            $this->get_offset(),
            array(
                new OrderBy(new PropertyConditionVariable(User :: class_name(), User :: PROPERTY_FIRSTNAME)),
                new OrderBy(new PropertyConditionVariable(User :: class_name(), User :: PROPERTY_LASTNAME))));

        return \Chamilo\Core\User\DataManager :: retrieves(User :: class_name(), $parameters);
    }
}
