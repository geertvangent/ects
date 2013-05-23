<?php
namespace application\atlantis\role\entity;

use common\libraries\AdvancedElementFinderElement;
use group\GroupAjaxPlatformGroupsFeed;
use common\libraries\Request;
use common\libraries\PatternMatchCondition;
use common\libraries\EqualityCondition;
use group\Group;
use common\libraries\OrCondition;
use common\libraries\InCondition;
use common\libraries\AndCondition;
use group\GroupDataManager;
use common\libraries\ObjectTableOrder;
use common\libraries\AdvancedElementFinderElements;
use common\libraries\Translation;

/**
 * Feed to return the platform groups for the platform group entity
 *
 * @package roup
 * @author Sven Vanpoucke
 */
class EntityAjaxPlatformGroupEntityFeed extends GroupAjaxPlatformGroupsFeed
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

    // /**
    // * Returns all the groups for this feed
    // *
    // * @return ResultSet
    // */
    // public function retrieve_groups()
    // {
    // // Set the conditions for the search query
    // $search_query = Request :: post(self :: PARAM_SEARCH_QUERY);
    // if ($search_query && $search_query != '')
    // {
    // $q = '*' . $search_query . '*';
    // $name_conditions[] = new PatternMatchCondition(Group :: PROPERTY_NAME, $q);
    // $name_conditions[] = new PatternMatchCondition(Group :: PROPERTY_CODE, $q);
    // $conditions[] = new OrCondition($name_conditions);
    // }

    // $filter_id = $this->get_filter();

    // if ($filter_id)
    // {
    // $conditions[] = new EqualityCondition(Group :: PROPERTY_PARENT, $filter_id);
    // }
    // else
    // {
    // $conditions[] = new EqualityCondition(Group :: PROPERTY_PARENT, 0);
    // }

    // // if (! $this->get_user()->is_platform_admin() &&
    // // \application\atlantis\rights\Rights :: get_instance()->access_is_allowed())
    // // {
    // // $target_groups = \application\atlantis\rights\Rights :: get_instance()->get_target_groups($this->get_user());

    // // if (count($target_groups) > 0)
    // // {
    // // $conditions[] = new InCondition(Group :: PROPERTY_ID, $target_groups);
    // // }
    // // else
    // // {
    // // $conditions[] = new EqualityCondition(Group :: PROPERTY_ID, - 1);
    // // }
    // // }

    // // Combine the conditions
    // $count = count($conditions);
    // if ($count > 1)
    // {
    // $condition = new AndCondition($conditions);
    // }

    // if ($count == 1)
    // {
    // $condition = $conditions[0];
    // }

    // return GroupDataManager :: get_instance()->retrieve_groups(
    // $condition,
    // null,
    // null,
    // array(new ObjectTableOrder(Group :: PROPERTY_NAME)));
    // }

    /**
     * Returns all the elements for this feed
     *
     * @return AdvancedElementFinderElements
     */
    public function get_elements()
    {
        $elements = new AdvancedElementFinderElements();

        // Add groups
        $groups = $this->retrieve_groups();

        // Target groups
        if (! $this->get_user()->is_platform_admin() &&
             \application\atlantis\rights\Rights :: get_instance()->access_is_allowed())
        {
            $target_groups = \application\atlantis\rights\Rights :: get_instance()->get_target_groups($this->get_user());
        }

        if ($groups && $groups->size() > 0)
        {
            // Add group category
            $group_category = new AdvancedElementFinderElement(
                'groups',
                'category',
                Translation :: get('Groups'),
                Translation :: get('Groups'));
            $elements->add_element($group_category);

            while ($group = $groups->next_result())
            {
                if ($this->get_user()->is_platform_admin())
                {
                    $group_category->add_child($this->get_group_element($group));
                }
                elseif (! $this->get_user()->is_platform_admin() &&
                     \application\atlantis\rights\Rights :: get_instance()->access_is_allowed())
                {
                    foreach ($target_groups as $target_group)
                    {
                        $is_parent = $group->is_parent_of($target_group);
                        $is_child = $group->is_child_of($target_group);

                        if ($is_parent || $is_child || $target_group == $group->get_id())
                        {
                            $group_category->add_child($this->get_group_element($group));
                            break;
                        }
                    }
                }
            }
        }

        $filter_id = $this->get_filter();

        if ($filter_id)
        {
            $add_users = false;

            if ($this->get_user()->is_platform_admin())
            {
                $add_users = true;
            }
            elseif (! $this->get_user()->is_platform_admin() &&
                 \application\atlantis\rights\Rights :: get_instance()->access_is_allowed())
            {
                $group = \group\DataManager :: get_instance()->retrieve_group($filter_id);

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
                // Add users
                $users = $this->retrieve_users();
                if ($users && $users->size() > 0)
                {
                    // Add user category
                    $user_category = new AdvancedElementFinderElement('users', 'category', 'Users', 'Users');
                    $elements->add_element($user_category);

                    while ($user = $users->next_result())
                    {
                        $user_category->add_child($this->get_user_element($user));
                    }
                }
            }
        }

        return $elements;
    }
}
