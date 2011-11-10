<?php
namespace application\discovery\module\teaching_assignment\implementation\bamaflex;

use group\GroupRelUser;

use common\libraries\Request;

use common\libraries\PatternMatchCondition;
use common\libraries\EqualityCondition;
use common\libraries\InCondition;
use common\libraries\AndCondition;
use common\libraries\ObjectTableOrder;

use common\libraries\AdvancedElementFinderElement;
use common\libraries\CommonAjaxGroupsFeed;

use group\Group;
use group\GroupDataManager;

class BamaflexAjaxPlatformGroupsFeed extends CommonAjaxGroupsFeed
{   
    const PARAM_MODULE_INSTANCE_ID = 'module_instance_id';
    const PARAM_PARAMETERS = 'parameters';

    function required_parameters()
    {
        return array(self :: PARAM_MODULE_INSTANCE_ID, self :: PARAM_PARAMETERS);
    }

    /**
     * Returns all the groups for this feed
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
        $filter_id = substr($filter, 6);
        
        if ($filter_id)
        {
            $conditions[] = new EqualityCondition(Group :: PROPERTY_PARENT, $filter_id);
        }
        else
        {
            $conditions[] = new EqualityCondition(Group :: PROPERTY_PARENT, 0);
        }
        
        $targets_entities = Rights :: get_instance()->get_module_targets_entities($this->get_parameter(self :: PARAM_MODULE_INSTANCE_ID), $this->get_parameter(self :: PARAM_PARAMETERS));
        $conditions[] = new InCondition(Group :: PROPERTY_ID, $targets_entities[RightsPlatformGroupEntity :: ENTITY_TYPE]);
        $condition = new AndCondition($conditions);
        
        return GroupDataManager :: get_instance()->retrieve_groups($condition, null, null, array(
                new ObjectTableOrder(Group :: PROPERTY_NAME)));
    }

    /**
     * Retrieves all the users for the selected group
     */
    function get_user_ids()
    {
        $filter = Request :: post(self :: PARAM_FILTER);
        $filter_id = substr($filter, 6);
        
        if (! $filter_id)
        {
            return;
        }
        
        $condition = new EqualityCondition(GroupRelUser:: PROPERTY_GROUP_ID, $filter_id);
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
     * @return AdvancedElementFinderElement
     */
    function get_group_element($group)
    {
        return new AdvancedElementFinderElement(RightsPlatformGroupEntity :: ENTITY_TYPE . '_' . $group->get_id(), 'type type_group', $group->get_name(), $group->get_code(), AdvancedElementFinderElement :: TYPE_SELECTABLE_AND_FILTER);
    }

    /**
     * Returns the element for a specific user
     * @return AdvancedElementFinderElement
     */
    function get_user_element($user)
    {
        return new AdvancedElementFinderElement(RightsUserEntity :: ENTITY_TYPE . '_' . $user->get_id(), 'type type_user', $user->get_fullname(), $user->get_official_code());
    }

}

?>