<?php
namespace Chamilo\Application\Discovery\Module\Person\Implementation\Chamilo;

use Chamilo\Libraries\Storage\AndCondition;
use Chamilo\Libraries\Utilities\Utilities;
use Chamilo\Application\Discovery\Parameters;
use Chamilo\Libraries\Storage\OrCondition;
use Chamilo\Libraries\Storage\PatternMatchCondition;
use Chamilo\Libraries\Storage\EqualityCondition;
use Chamilo\Libraries\Platform\Request;
use Chamilo\Libraries\Storage\DataClassRetrieveParameters;
use Chamilo\Libraries\Storage\PropertyConditionVariable;
use Chamilo\Libraries\Storage\StaticConditionVariable;

class Module extends \Chamilo\Application\Discovery\Module\Person\Module
{

    public function get_group()
    {
        if (! $this->group)
        {
            $this->group = Request :: get(\Chamilo\Core\Group\Manager :: PARAM_GROUP_ID);
            
            if (! $this->group)
            {
                $this->group = $this->get_root_group()->get_id();
            }
        }
        
        return $this->group;
    }

    public function get_root_group()
    {
        if (! $this->root_group)
        {
            $group = \Chamilo\Core\Group\DataManager :: retrieve(
                \Chamilo\Core\Group\Group :: class_name(), 
                new DataClassRetrieveParameters(
                    new EqualityCondition(
                        new PropertyConditionVariable(
                            \Chamilo\Core\Group\Group :: class_name(), 
                            \Chamilo\Core\Group\Group :: PROPERTY_PARENT_ID), 
                        new StaticConditionVariable(0))));
            $this->root_group = $group;
        }
        
        return $this->root_group;
    }

    public function get_subgroups_condition($query = null)
    {
        if (isset($query) && $query != '')
        {
            $or_conditions = array();
            $or_conditions[] = new PatternMatchCondition(
                new PropertyConditionVariable(\Chamilo\Core\Group\Group :: class_name(), \Chamilo\Core\Group\Group :: PROPERTY_NAME), 
                '*' . $query . '*');
            $or_conditions[] = new PatternMatchCondition(
                new PropertyConditionVariable(
                    \Chamilo\Core\Group\Group :: class_name(), 
                    \Chamilo\Core\Group\Group :: PROPERTY_DESCRIPTION), 
                '*' . $query . '*');
            $or_conditions[] = new PatternMatchCondition(
                new PropertyConditionVariable(\Chamilo\Core\Group\Group :: class_name(), \Chamilo\Core\Group\Group :: PROPERTY_CODE), 
                '*' . $query . '*');
            return new OrCondition($or_conditions);
        }
        else
        {
            $condition = new EqualityCondition(
                new PropertyConditionVariable(\Chamilo\Core\Group\Group :: class_name(), \Chamilo\Core\Group\Group :: PROPERTY_PARENT_ID), 
                new StaticConditionVariable($this->get_group()));
        }
        
        return $condition;
    }

    public function get_users_condition($query = null)
    {
        if (isset($query) && $query != '')
        {
            return self :: query_to_condition($query);
        }
        else
        {
            return new EqualityCondition(
                new PropertyConditionVariable(
                    \Chamilo\Core\Group\GroupRelUser :: class_name(), 
                    \Chamilo\Core\Group\GroupRelUser :: PROPERTY_GROUP_ID), 
                new StaticConditionVariable($this->get_group()));
        }
    }

    public static function query_to_condition($query)
    {
        $queries = Utilities :: split_query($query);
        
        if (is_null($queries))
        {
            return null;
        }
        
        $conditions = array();
        
        foreach ($queries as $query)
        {
            $pattern_conditions = array();
            $pattern_conditions[] = new PatternMatchCondition(
                new PropertyConditionVariable(\Chamilo\Core\User\User :: class_name(), \Chamilo\Core\User\User :: PROPERTY_FIRSTNAME), 
                '*' . $query . '*');
            $pattern_conditions[] = new PatternMatchCondition(
                new PropertyConditionVariable(\Chamilo\Core\User\User :: class_name(), \Chamilo\Core\User\User :: PROPERTY_LASTNAME), 
                '*' . $query . '*');
            $pattern_conditions[] = new PatternMatchCondition(
                new PropertyConditionVariable(\Chamilo\Core\User\User :: class_name(), \Chamilo\Core\User\User :: PROPERTY_USERNAME), 
                '*' . $query . '*');
            $pattern_conditions[] = new PatternMatchCondition(
                new PropertyConditionVariable(\Chamilo\Core\User\User :: class_name(), \Chamilo\Core\User\User :: PROPERTY_OFFICIAL_CODE), 
                '*' . $query . '*');
            
            $conditions[] = new OrCondition($pattern_conditions);
        }
        
        return new AndCondition($conditions);
    }

    public static function module_parameters()
    {
        return new Parameters();
    }
}
