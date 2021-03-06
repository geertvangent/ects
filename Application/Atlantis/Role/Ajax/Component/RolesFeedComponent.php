<?php
namespace Ehb\Application\Atlantis\Role\Ajax\Component;

use Chamilo\Libraries\Architecture\JsonAjaxResult;
use Chamilo\Libraries\Format\Form\Element\AdvancedElementFinder\AdvancedElementFinderElement;
use Chamilo\Libraries\Format\Form\Element\AdvancedElementFinder\AdvancedElementFinderElements;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;
use Chamilo\Libraries\Storage\Query\Condition\AndCondition;
use Chamilo\Libraries\Storage\Query\OrderBy;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Utilities\Utilities;
use Ehb\Application\Atlantis\Role\Storage\DataClass\Role;
use Ehb\Application\Atlantis\Role\Storage\DataManager;

class RolesFeedComponent extends \Ehb\Application\Atlantis\Role\Ajax\Manager
{
    const PARAM_SEARCH_QUERY = 'query';
    const PARAM_OFFSET = 'offset';
    const PROPERTY_TOTAL_ELEMENTS = 'total_elements';
    const PROPERTY_ELEMENTS = 'elements';

    private $role_count = 0;

    /**
     * Returns the required parameters
     * 
     * @return string[]
     */
    public function getRequiredPostParameters()
    {
        return array();
    }

    /**
     * Runs this ajax component
     */
    public function run()
    {
        $result = new JsonAjaxResult();
        
        $search_query = Request::post(self::PARAM_SEARCH_QUERY);
        
        $elements = $this->get_elements();
        
        $elements = $elements->as_array();
        
        $result->set_property(self::PROPERTY_ELEMENTS, $elements);
        $result->set_property(self::PROPERTY_TOTAL_ELEMENTS, $this->role_count);
        
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
        
        // Add role category
        $role_category = new AdvancedElementFinderElement('roles', 'category', Translation::get('Roles'));
        $elements->add_element($role_category);
        
        $roles = $this->retrieve_roles();
        if ($roles)
        {
            while ($role = $roles->next_result())
            {
                $role_category->add_child($this->get_element_for_role($role));
            }
        }
        
        return $elements;
    }

    private function retrieve_roles()
    {
        $search_query = Request::post(self::PARAM_SEARCH_QUERY);
        
        // Set the conditions for the search query
        if ($search_query && $search_query != '')
        {
            $conditions[] = Utilities::query_to_condition(
                $search_query, 
                array(Role::PROPERTY_NAME, Role::PROPERTY_DESCRIPTION));
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
        
        $this->role_count = DataManager::count(Role::class_name(), $condition);
        $parameters = new DataClassRetrievesParameters(
            $condition, 
            100, 
            $this->get_offset(), 
            array(new OrderBy(new PropertyConditionVariable(Role::class_name(), Role::PROPERTY_NAME))));
        
        return DataManager::retrieves(Role::class_name(), $parameters);
    }

    /**
     * Returns the selected offset
     * 
     * @return int
     */
    protected function get_offset()
    {
        $offset = Request::post(self::PARAM_OFFSET);
        if (! isset($offset) || is_null($offset))
        {
            $offset = 0;
        }
        
        return $offset;
    }

    protected function get_element_for_role($role)
    {
        return new AdvancedElementFinderElement(
            'role_' . $role->get_id(), 
            'type type_role', 
            $role->get_name(), 
            $role->get_description());
    }
}
