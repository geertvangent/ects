<?php
namespace Ehb\Application\Atlantis\Context\Ajax\Component;

use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;
use Chamilo\Libraries\Storage\Query\Condition\AndCondition;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\OrderBy;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Ehb\Application\Atlantis\Context\Storage\DataManager;
use Ehb\Application\Atlantis\UserGroup\Storage\DataClass\Context;

class MenuComponent extends \Ehb\Application\Atlantis\Context\Ajax\Manager
{

    public function run()
    {
        $context_tree = array();
        
        $parent_id = Request::get('parent_id');
        
        $context = DataManager::retrieve_by_id(Context::class_name(), (int) $parent_id);
        $conditions = array();
        $conditions[] = new EqualityCondition(
            new PropertyConditionVariable(Context::class_name(), Context::PROPERTY_PARENT_ID), 
            new StaticConditionVariable($context->get_context_id()));
        $conditions[] = new EqualityCondition(
            new PropertyConditionVariable(Context::class_name(), Context::PROPERTY_PARENT_TYPE), 
            new StaticConditionVariable($context->get_context_type()));
        $condition = new AndCondition($conditions);
        
        $contexts = DataManager::retrieves(
            Context::class_name(), 
            new DataClassRetrievesParameters(
                $condition, 
                null, 
                null, 
                array(new OrderBy(new PropertyConditionVariable(Context::class_name(), Context::PROPERTY_CONTEXT_NAME)))))->as_array();
        
        header('Content-Type: text/xml');
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n", '<tree>' . "\n";
        echo $this->dump_tree($contexts);
        echo '</tree>';
    }

    function dump_tree($contexts)
    {
        $html = array();
        
        if ($this->contains_results($contexts))
        {
            
            $this->dump_contexts_tree($contexts);
        }
    }

    function dump_contexts_tree($contexts)
    {
        foreach ($contexts as $context)
        {
            $has_children = $context->has_children() ? 1 : 0;
            echo '<leaf id="' . $context->get_id() . '" classes="category" has_children="' . $has_children . '" title="' .
                 htmlspecialchars($context->get_context_name()) . '" description="' .
                 htmlspecialchars($context->get_context_name()) . '"/>' . "\n";
        }
    }

    function contains_results($objects)
    {
        if (count($objects))
        {
            return true;
        }
        return false;
    }
}