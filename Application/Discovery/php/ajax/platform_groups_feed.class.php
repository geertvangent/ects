<?php
namespace application\discovery;

use common\libraries\Request;
use common\libraries\PatternMatchCondition;
use common\libraries\EqualityCondition;
use common\libraries\InCondition;
use common\libraries\AndCondition;
use common\libraries\ObjectTableOrder;
use common\libraries\AdvancedElementFinderElement;
use common\libraries\CommonAjaxGroupsFeed;
use group\Group;
use group\GroupRelUser;
use group\GroupDataManager;

class DiscoveryAjaxPlatformGroupsFeed extends CommonAjaxGroupsFeed
{
    const PARAM_PUBLICATION = 'publication_id';

    function required_parameters()
    {
        return array();
    }

    /**
     * Returns all the groups for this feed
     *
     * @return ResultSet
     */
    function retrieve_groups()
    {
        // Set the conditions for the search query
        $search_query = Request :: post(self :: PARAM_SEARCH_QUERY);
        if ($search_query && $search_query != '')
        {
            $q = '*' . $search_query . '*';
            $conditions[] = new PatternMatchCondition(Group :: PROPERTY_NAME, $q);
        }

        // Set the filter conditions
        $filter = Request :: post(self :: PARAM_FILTER);
        $filter_id = substr($filter, 2);
        if ($filter_id)
        {
            $conditions[] = new EqualityCondition(Group :: PROPERTY_PARENT, $filter_id);
        }
        else
        {
            $conditions[] = new EqualityCondition(Group :: PROPERTY_PARENT, 0);
        }

        // $targets_entities = PhrasesRights :: get_instance()->get_phrases_targets_entities($this->get_parameter(self
        // :: PARAM_PUBLICATION));
        // $conditions[] = new InCondition(Group :: PROPERTY_ID, $targets_entities[PublicationPlatformGroupEntity ::
        // ENTITY_TYPE]);
        $condition = new AndCondition($conditions);

        return GroupDataManager :: get_instance()->retrieve_groups($condition, null, null,
                array(new ObjectTableOrder(Group :: PROPERTY_NAME)));
    }

    /**
     * Retrieves all the users for the selected group
     */
    function get_user_ids()
    {
        $filter = Request :: post(self :: PARAM_FILTER);
        $filter_id = substr($filter, 2);

        if (! $filter_id)
        {
            return;
        }

        $condition = new EqualityCondition(GroupRelUser :: PROPERTY_GROUP_ID, $filter_id);
        $relations = GroupDataManager :: get_instance()->retrieve_group_rel_users($condition);

        $user_ids = array();

        while ($relation = $relations->next_result())
        {
            $user_ids[] = $relation->get_user_id();
        }

        return $user_ids;
    }

    /**
     * Returns the element for a specific group
     *
     * @return AdvancedElementFinderElement
     */
    function get_group_element($group)
    {
        return new AdvancedElementFinderElement(PlatformGroupEntity :: ENTITY_TYPE . '_' . $group->get_id(),
                'type type_group', $group->get_name(), $group->get_code(),
                AdvancedElementFinderElement :: TYPE_SELECTABLE_AND_FILTER);
    }

    /**
     * Returns the element for a specific user
     *
     * @return AdvancedElementFinderElement
     */
    function get_user_element($user)
    {
        return new AdvancedElementFinderElement(UserEntity :: ENTITY_TYPE . '_' . $user->get_id(), 'type type_user',
                $user->get_fullname(), $user->get_official_code());
    }
}
