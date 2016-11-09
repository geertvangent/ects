<?php
namespace Ehb\Application\Atlantis\Role\Entity\Ajax\Component;

use Chamilo\Core\Group\Ajax\Component\PlatformGroupsFeedComponent;
use Chamilo\Core\Group\Storage\DataClass\Group;
use Chamilo\Libraries\Format\Form\Element\AdvancedElementFinder\AdvancedElementFinderElement;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Storage\Parameters\DataClassCountParameters;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;
use Chamilo\Libraries\Storage\Query\Condition\AndCondition;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\Condition\NotCondition;
use Chamilo\Libraries\Storage\Query\Condition\OrCondition;
use Chamilo\Libraries\Storage\Query\Condition\PatternMatchCondition;
use Chamilo\Libraries\Storage\Query\OrderBy;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Chamilo\Libraries\Storage\ResultSet\ArrayResultSet;
use Ehb\Application\Atlantis\Role\Entity\Entities\PlatformGroupEntity;
use Ehb\Application\Atlantis\Role\Entity\Entities\UserEntity;

/**
 * Feed to return the platform groups for the platform group entity
 *
 * @package roup
 * @author Sven Vanpoucke
 */
class ContextsFeedComponent extends PlatformGroupsFeedComponent
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
        $code_conditions = array();
        $code_conditions[] = new PatternMatchCondition(
            new PropertyConditionVariable(Group :: class_name(), Group :: PROPERTY_CODE),
            'AY_*');
        $code_conditions[] = new PatternMatchCondition(
            new PropertyConditionVariable(Group :: class_name(), Group :: PROPERTY_CODE),
            'CA*');
        $code_conditions[] = new PatternMatchCondition(
            new PropertyConditionVariable(Group :: class_name(), Group :: PROPERTY_CODE),
            'DEP_*');
        $code_conditions[] = new PatternMatchCondition(
            new PropertyConditionVariable(Group :: class_name(), Group :: PROPERTY_CODE),
            'TRA_OP_*');
        $code_conditions[] = new PatternMatchCondition(
            new PropertyConditionVariable(Group :: class_name(), Group :: PROPERTY_CODE),
            'TRA_STU_*');

        $not_code_conditions = array();
        $not_code_conditions[] = new NotCondition(
            new PatternMatchCondition(
                new PropertyConditionVariable(Group :: class_name(), Group :: PROPERTY_CODE),
                'TRA_STU_*_*'));
        $not_code_conditions[] = new NotCondition(
            new PatternMatchCondition(
                new PropertyConditionVariable(Group :: class_name(), Group :: PROPERTY_CODE),
                'COU_OP_*'));

        $conditions = array();
        $conditions[] = new OrCondition($code_conditions);
        $conditions[] = new AndCondition($not_code_conditions);
        $conditions[] = new EqualityCondition(
            new PropertyConditionVariable(Group :: class_name(), Group :: PROPERTY_PARENT_ID),
            new StaticConditionVariable($group->get_id()));
        $condition = new AndCondition($conditions);

        $children_count = \Chamilo\Core\Group\Storage\DataManager :: count(
            Group :: class_name(),
            new DataClassCountParameters($condition));

        if ($this->get_user()->is_platform_admin())
        {
            if ($children_count > 0)
            {
                $element_type = AdvancedElementFinderElement :: TYPE_SELECTABLE_AND_FILTER;
                $element_class = 'type type_context_folder';
            }
            else
            {
                $element_type = AdvancedElementFinderElement :: TYPE_SELECTABLE;
                $element_class = 'type type_context_simple';
            }
        }
        elseif (\Ehb\Application\Atlantis\Rights :: getInstance()->access_is_allowed())
        {
            $target_groups = \Ehb\Application\Atlantis\Rights :: getInstance()->get_target_groups($this->get_user());

            if (in_array($group->get_id(), $target_groups))
            {
                if ($children_count > 0)
                {
                    $element_type = AdvancedElementFinderElement :: TYPE_SELECTABLE_AND_FILTER;
                    $element_class = 'type type_context_folder';
                }
                else
                {
                    $element_type = AdvancedElementFinderElement :: TYPE_SELECTABLE;
                    $element_class = 'type type_context_simple';
                }
            }
            else
            {
                foreach ($target_groups as $target_group)
                {
                    if ($group->is_child_of($target_group))
                    {
                        if ($children_count > 0)
                        {
                            $element_type = AdvancedElementFinderElement :: TYPE_SELECTABLE_AND_FILTER;
                            $element_class = 'type type_context_folder';
                        }
                        else
                        {
                            $element_type = AdvancedElementFinderElement :: TYPE_SELECTABLE;
                            $element_class = 'type type_context_simple';
                        }
                        break;
                    }
                }

                // if (! $type)
                // {
                // $element_type = AdvancedElementFinderElement :: TYPE_FILTER;
                // $element_class = 'type type_context_folder';
                // }
            }
        }
        else
        {
            $element_type = AdvancedElementFinderElement :: TYPE_FILTER;
            $element_class = 'type type_context_folder';
        }

        return new AdvancedElementFinderElement(
            PlatformGroupEntity :: ENTITY_TYPE . '_' . $group->get_id(),
            $element_class,
            $group->get_name(),
            $group->get_code(),
            $element_type);
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

        $code_conditions = array();
        $code_conditions[] = new PatternMatchCondition(
            new PropertyConditionVariable(Group :: class_name(), Group :: PROPERTY_CODE),
            'AY_*');
        $code_conditions[] = new PatternMatchCondition(
            new PropertyConditionVariable(Group :: class_name(), Group :: PROPERTY_CODE),
            'CA*');
        $code_conditions[] = new PatternMatchCondition(
            new PropertyConditionVariable(Group :: class_name(), Group :: PROPERTY_CODE),
            'DEP_*');
        $code_conditions[] = new PatternMatchCondition(
            new PropertyConditionVariable(Group :: class_name(), Group :: PROPERTY_CODE),
            'TRA_OP_*');
        $code_conditions[] = new PatternMatchCondition(
            new PropertyConditionVariable(Group :: class_name(), Group :: PROPERTY_CODE),
            'TRA_STU_*');
        $code_conditions[] = new EqualityCondition(
            new PropertyConditionVariable(Group :: class_name(), Group :: PROPERTY_PARENT_ID),
            new StaticConditionVariable(0));

        $conditions[] = new OrCondition($code_conditions);

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

        $groups = \Chamilo\Core\Group\Storage\DataManager :: retrieves(
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
        elseif (\Ehb\Application\Atlantis\Rights :: getInstance()->access_is_allowed())
        {
            $target_groups = \Ehb\Application\Atlantis\Rights :: getInstance()->get_target_groups($this->get_user());

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
        return;
    }
}
