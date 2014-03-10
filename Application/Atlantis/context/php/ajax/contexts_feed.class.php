<?php
namespace application\atlantis\context;

use common\libraries\JsonAjaxResult;
use common\libraries\DataClassRetrievesParameters;
use common\libraries\AdvancedElementFinderElements;
use common\libraries\Translation;
use common\libraries\AjaxManager;
use common\libraries\Request;
use common\libraries\PatternMatchCondition;
use common\libraries\EqualityCondition;
use common\libraries\AndCondition;
use common\libraries\ObjectTableOrder;
use common\libraries\AdvancedElementFinderElement;
use common\libraries\DataClassCache;

class ContextAjaxContextsFeed extends AjaxManager
{
    const PARAM_SEARCH_QUERY = 'query';
    const PARAM_OFFSET = 'offset';
    const PARAM_FILTER = 'filter';
    const PROPERTY_ELEMENTS = 'elements';
    const PROPERTY_TOTAL_ELEMENTS = 'total_elements';
    const FILTER_PREFIX_LENGTH = 8;

    protected $context_count = 0;

    /**
     * Runs this ajax component
     */
    public function run()
    {
        $result = new JsonAjaxResult();

        $elements = $this->get_elements();
        $elements = $elements->as_array();

        $result->set_property(self :: PROPERTY_ELEMENTS, $elements);

        if ($this->context_count > 0)
        {
            $result->set_property(self :: PROPERTY_TOTAL_ELEMENTS, $this->context_count);
        }

        $result->display();
    }

    /**
     * Returns all the elements for this feed
     *
     * @return AdvancedElementFinderElements
     */
    private function get_elements()
    {
        $elements = new AdvancedElementFinderElements();

        // Target groups
        if (! $this->get_user()->is_platform_admin() &&
             \application\atlantis\rights\Rights :: get_instance()->access_is_allowed())
        {
            $target_groups = \application\atlantis\rights\Rights :: get_instance()->get_target_groups($this->get_user());
        }

        $contexts = $this->retrieve_contexts();
        if ($contexts && $contexts->size() > 0)
        {
            $context_category = new AdvancedElementFinderElement('contexts', 'category', Translation :: get('Contexts'));
            $elements->add_element($context_category);

            while ($context = $contexts->next_result())
            {
                if ($this->get_user()->is_platform_admin())
                {
                    $context_category->add_child($this->get_context_element($context));
                }
                elseif (! $this->get_user()->is_platform_admin() &&
                     \application\atlantis\rights\Rights :: get_instance()->access_is_allowed())
                {
                    DataClassCache :: truncate(\group\Group :: class_name());

                    $group = \group\DataManager :: retrieve_group_by_code($context->get_context_id());

                    if ($group instanceof \group\Group)
                    {
                        foreach ($target_groups as $target_group)
                        {
                            $is_parent = $group->is_parent_of($target_group);
                            $is_child = $group->is_child_of($target_group);

                            if ($is_parent || $is_child || $target_group == $group->get_id())
                            {
                                $context_category->add_child($this->get_context_element($context));
                                break;
                            }
                        }
                    }
                }
            }
        }

        return $elements;
    }

    protected function get_offset()
    {
        $offset = Request :: post(self :: PARAM_OFFSET);
        if (! isset($offset) || is_null($offset))
        {
            $offset = 0;
        }

        return $offset;
    }

    /**
     * The length for the filter prefix to remove
     */
    public function required_parameters()
    {
        return array();
    }

    /**
     * Returns all the contexts for this feed
     *
     * @return ResultSet
     */
    public function retrieve_contexts()
    {
        // Set the conditions for the search query
        $search_query = Request :: post(self :: PARAM_SEARCH_QUERY);
        if ($search_query && $search_query != '')
        {
            $q = '*' . $search_query . '*';
            $conditions[] = new PatternMatchCondition(Context :: PROPERTY_CONTEXT_NAME, $q);
        }

        $filter_id = $this->get_filter();

        if ($filter_id)
        {
            $context = DataManager :: retrieve(Context :: class_name(), (int) $filter_id);
            $conditions[] = new EqualityCondition(Context :: PROPERTY_PARENT_TYPE, $context->get_context_type());
            $conditions[] = new EqualityCondition(Context :: PROPERTY_PARENT_ID, $context->get_id());
        }
        else
        {
            $conditions[] = new EqualityCondition(Context :: PROPERTY_PARENT_ID, '0');
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

        $parameters = new DataClassRetrievesParameters(
            $condition,
            null,
            null,
            array(new ObjectTableOrder(Context :: PROPERTY_CONTEXT_NAME)));
        return DataManager :: retrieves(Context :: class_name(), $parameters);
    }

    /**
     * Returns the element for a specific context
     *
     * @return AdvancedElementFinderElement
     */
    public function get_context_element($context)
    {
        if ($this->get_user()->is_platform_admin())
        {
            if ($context->get_context_type() != 4 && $context->get_context_type() != 7)
            {
                $element_type = AdvancedElementFinderElement :: TYPE_SELECTABLE_AND_FILTER;
                $element_class = 'type type_context_folder';
            }
            else
            {
                $element_class = 'type type_context_simple';
                $element_type = AdvancedElementFinderElement :: TYPE_SELECTABLE;
            }
        }
        elseif (\application\atlantis\rights\Rights :: get_instance()->access_is_allowed())
        {
            if ($context->get_context_type() != 4 && $context->get_context_type() != 7)
            {
                $group = \group\DataManager :: retrieve_group_by_code($context->get_context_id());

                $target_groups = \application\atlantis\rights\Rights :: get_instance()->get_target_groups(
                    $this->get_user());

                foreach ($target_groups as $target_group)
                {
                    if ($target_group == $group->get_id())
                    {
                        $element_type = AdvancedElementFinderElement :: TYPE_SELECTABLE_AND_FILTER;
                    }
                    elseif ($group->is_child_of($target_group))
                    {
                        $element_type = AdvancedElementFinderElement :: TYPE_SELECTABLE_AND_FILTER;
                    }
                    elseif ($group->is_parent_of($target_group))
                    {
                        $element_type = AdvancedElementFinderElement :: TYPE_FILTER;
                    }
                }

                $element_class = 'type type_context_folder';
            }
            else
            {
                $element_type = AdvancedElementFinderElement :: TYPE_SELECTABLE;
                $element_class = 'type type_context_simple';
            }
        }

        return new AdvancedElementFinderElement(
            'context_' . $context->get_id(),
            $element_class,
            $context->get_context_name(),
            $context->get_context_name(),
            $element_type);
    }

    /**
     * Returns the id of the selected filter
     */
    protected function get_filter()
    {
        $filter = Request :: post(self :: PARAM_FILTER);
        return substr($filter, static :: FILTER_PREFIX_LENGTH);
    }
}
