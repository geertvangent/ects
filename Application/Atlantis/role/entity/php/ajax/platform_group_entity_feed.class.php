<?php
namespace application\atlantis\role\entity;

use libraries\format\AdvancedElementFinderElement;
use libraries\storage\PatternMatchCondition;
use libraries\platform\Request;
use libraries\storage\OrCondition;
use libraries\storage\EqualityCondition;
use libraries\storage\AndCondition;
use libraries\storage\DataClassRetrievesParameters;
use libraries\storage\ArrayResultSet;
use core\group\AjaxPlatformGroupsFeed;
use core\group\Group;
use core\group\GroupRelUser;
use libraries\storage\PropertyConditionVariable;
use libraries\storage\OrderBy;
use libraries\storage\StaticConditionVariable;

/**
 * Feed to return the platform groups for the platform group entity
 *
 * @package roup
 * @author Sven Vanpoucke
 */
class EntityAjaxPlatformGroupEntityFeed extends AjaxPlatformGroupsFeed
{
    /**
     * The length for the filter prefix to remove
     */
    const FILTER_PREFIX_LENGTH = 2;

    /**
     * Returns the element for a specific group
     *
     * @return AdvancedElementFinderElement
     */
    public function get_group_element($group)
    {
        if ($this->get_user()->is_platform_admin())
        {
            $type = AdvancedElementFinderElement :: TYPE_SELECTABLE_AND_FILTER;
        }
        elseif (\application\atlantis\rights\Rights :: get_instance()->access_is_allowed())
        {
            $target_groups = \application\atlantis\rights\Rights :: get_instance()->get_target_groups($this->get_user());

            if (in_array($group->get_id(), $target_groups))
            {
                $type = AdvancedElementFinderElement :: TYPE_SELECTABLE_AND_FILTER;
            }
            else
            {
                foreach ($target_groups as $target_group)
                {
                    if ($group->is_child_of($target_group))
                    {
                        $type = AdvancedElementFinderElement :: TYPE_SELECTABLE_AND_FILTER;
                        break;
                    }
                }

                if (! $type)
                {
                    $type = AdvancedElementFinderElement :: TYPE_FILTER;
                }
            }
        }
        else
        {
            $type = AdvancedElementFinderElement :: TYPE_FILTER;
        }

        return new AdvancedElementFinderElement(
            PlatformGroupEntity :: ENTITY_TYPE . '_' . $group->get_id(),
            'type type_group',
            $group->get_name(),
            $group->get_code(),
            $type);
    }

    /**
     * Returns the element for a specific user
     *
     * @return AdvancedElementFinderElement
     */
    public function get_user_element($user)
    {
        return new AdvancedElementFinderElement(
            UserEntity :: ENTITY_TYPE . '_' . $user->get_id(),
            'type type_user',
            $user->get_fullname(),
            $user->get_official_code());
    }

    /**
     * Returns all the groups for this feed
     *
     * @return ResultSet
     */
    public function retrieve_groups()
    {
        // Set the conditions for the search query
        $search_query = Request :: post(self :: PARAM_SEARCH_QUERY);
        if ($search_query && $search_query != '')
        {
            $q = '*' . $search_query . '*';
            $name_conditions[] = new PatternMatchCondition(
                new PropertyConditionVariable(Group :: class_name(), Group :: PROPERTY_NAME),
                $q);
            $name_conditions[] = new PatternMatchCondition(
                new PropertyConditionVariable(Group :: class_name(), Group :: PROPERTY_CODE),
                $q);
            $conditions[] = new OrCondition($name_conditions);
        }

        $filter_id = $this->get_filter();

        if ($filter_id)
        {
            $conditions[] = new EqualityCondition(
                new PropertyConditionVariable(Group :: class_name(), Group :: PROPERTY_PARENT_ID),
                new StaticConditionVariable($filter_id));
        }
        else
        {
            $conditions[] = new EqualityCondition(
                new PropertyConditionVariable(Group :: class_name(), Group :: PROPERTY_PARENT_ID),
                new StaticConditionVariable(0));
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

        $groups = \core\group\DataManager :: retrieves(
            Group :: class_name(),
            new DataClassRetrievesParameters(
                $condition,
                null,
                null,
                array(new OrderBy(new PropertyConditionVariable(Group :: class_name(), Group :: PROPERTY_NAME)))));

        if ($this->get_user()->is_platform_admin())
        {
            return $groups;
        }
        elseif (\application\atlantis\rights\Rights :: get_instance()->access_is_allowed())
        {
            $target_groups = \application\atlantis\rights\Rights :: get_instance()->get_target_groups($this->get_user());

            $allowed_groups = array();

            while ($group = $groups->next_result())
            {
                foreach ($target_groups as $target_group)
                {
                    $is_parent = $group->is_parent_of($target_group);
                    $is_child = $group->is_child_of($target_group);

                    if ($is_parent || $is_child || $target_group == $group->get_id())
                    {
                        $allowed_groups[] = $group;
                        break;
                    }
                }
            }

            return new ArrayResultSet($allowed_groups);
        }
    }

    /**
     * Retrieves all the users for the selected group
     */
    public function get_user_ids()
    {
        $filter_id = $this->get_filter();

        if (! $filter_id)
        {
            return;
        }

        $add_users = false;

        if ($this->get_user()->is_platform_admin())
        {
            $add_users = true;
        }
        elseif (! $this->get_user()->is_platform_admin() &&
             \application\atlantis\rights\Rights :: get_instance()->access_is_allowed())
        {
            $group = \core\group\DataManager :: retrieve_by_id(Group :: class_name(), (int) $filter_id);
            $target_groups = \application\atlantis\rights\Rights :: get_instance()->get_target_groups($this->get_user());

            foreach ($target_groups as $target_group)
            {
                $is_child = $group->is_child_of($target_group);

                if ($is_child || $target_group == $group->get_id())
                {
                    $add_users = true;
                    break;
                }
            }
        }

        if ($add_users)
        {
            $condition = new EqualityCondition(
                new PropertyConditionVariable(GroupRelUser :: class_name(), GroupRelUser :: PROPERTY_GROUP_ID),
                new StaticConditionVariable($filter_id));
            $relations = \core\group\DataManager :: retrieves(GroupRelUser :: class_name(), $condition);

            $user_ids = array();

            while ($relation = $relations->next_result())
            {
                $user_ids[] = $relation->get_user_id();
            }

            return $user_ids;
        }
        else
        {
            return;
        }
    }
}
