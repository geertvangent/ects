<?php
namespace Ehb\Application\Atlantis\UserGroup\Ajax\Component;

use Chamilo\Libraries\Architecture\JsonAjaxResult;
use Chamilo\Libraries\Format\Form\Element\AdvancedElementFinder\AdvancedElementFinderElement;
use Chamilo\Libraries\Format\Form\Element\AdvancedElementFinder\AdvancedElementFinderElements;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;
use Chamilo\Libraries\Storage\Query\Condition\AndCondition;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\Condition\PatternMatchCondition;
use Chamilo\Libraries\Storage\Query\OrderBy;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Ehb\Application\Atlantis\UserGroup\Storage\DataClass\Context;
use Ehb\Application\Atlantis\UserGroup\Storage\DataManager;

class ContextsFeedComponent extends \Ehb\Application\Atlantis\UserGroup\Ajax\Manager
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
        
        $result->set_property(self::PROPERTY_ELEMENTS, $elements);
        
        if ($this->context_count > 0)
        {
            $result->set_property(self::PROPERTY_TOTAL_ELEMENTS, $this->context_count);
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
        
        $contexts = $this->retrieve_contexts();
        if ($contexts && $contexts->size() > 0)
        {
            $context_category = new AdvancedElementFinderElement('contexts', 'category', Translation::get('Contexts'));
            $elements->add_element($context_category);
            
            while ($context = $contexts->next_result())
            {
                $context_category->add_child($this->get_context_element($context));
            }
        }
        
        return $elements;
    }

    protected function get_offset()
    {
        $offset = Request::post(self::PARAM_OFFSET);
        if (! isset($offset) || is_null($offset))
        {
            $offset = 0;
        }
        
        return $offset;
    }

    /**
     * The length for the filter prefix to remove
     */
    public function getRequiredPostParameters()
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
        $search_query = Request::post(self::PARAM_SEARCH_QUERY);
        if ($search_query && $search_query != '')
        {
            $q = '*' . $search_query . '*';
            $conditions[] = new PatternMatchCondition(
                new PropertyConditionVariable(Context::class_name(), Context::PROPERTY_CONTEXT_NAME), 
                $q);
        }
        
        $filter_id = $this->get_filter();
        
        if ($filter_id)
        {
            $context = DataManager::retrieve_by_id(Context::class_name(), (int) $filter_id);
            $conditions[] = new EqualityCondition(
                new PropertyConditionVariable(Context::class_name(), Context::PROPERTY_PARENT_TYPE), 
                new StaticConditionVariable($context->get_context_type()));
            $conditions[] = new EqualityCondition(
                new PropertyConditionVariable(Context::class_name(), Context::PROPERTY_PARENT_ID), 
                new StaticConditionVariable($context->get_context_id()));
        }
        else
        {
            $conditions[] = new EqualityCondition(
                new PropertyConditionVariable(Context::class_name(), Context::PROPERTY_PARENT_ID), 
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
        
        $parameters = new DataClassRetrievesParameters(
            $condition, 
            null, 
            null, 
            array(new OrderBy(new PropertyConditionVariable(Context::class_name(), Context::PROPERTY_CONTEXT_NAME))));
        return DataManager::retrieves(Context::class_name(), $parameters);
    }

    /**
     * Returns the element for a specific context
     * 
     * @return AdvancedElementFinderElement
     */
    public function get_context_element($context)
    {
        return new AdvancedElementFinderElement(
            'context_' . $context->get_id(), 
            'type type_context', 
            $context->get_context_name(), 
            $context->get_context_name(), 
            AdvancedElementFinderElement::TYPE_SELECTABLE_AND_FILTER);
    }

    /**
     * Returns the id of the selected filter
     */
    protected function get_filter()
    {
        $filter = Request::post(self::PARAM_FILTER);
        return substr($filter, static::FILTER_PREFIX_LENGTH);
    }
}
