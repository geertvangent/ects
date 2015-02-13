<?php
namespace Ehb\Application\Discovery\Module\Person\Implementation\Chamilo;

use Ehb\Application\Discovery\Parameters;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrieveParameters;
use Chamilo\Libraries\Storage\Query\Condition\AndCondition;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\Condition\OrCondition;
use Chamilo\Libraries\Storage\Query\Condition\PatternMatchCondition;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Chamilo\Libraries\Utilities\Utilities;

class Module extends \Ehb\Application\Discovery\Module\Person\Module
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
            $group = \Chamilo\Core\Group\Storage\DataManager :: retrieve(
                \Chamilo\Core\Group\Storage\DataClass\Group :: class_name(), 
                new DataClassRetrieveParameters(
                    new EqualityCondition(
                        new PropertyConditionVariable(
                            \Chamilo\Core\Group\Storage\DataClass\Group :: class_name(), 
                            \Chamilo\Core\Group\Storage\DataClass\Group :: PROPERTY_PARENT_ID), 
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
                new PropertyConditionVariable(
                    \Chamilo\Core\Group\Storage\DataClass\Group :: class_name(), 
                    \Chamilo\Core\Group\Storage\DataClass\Group :: PROPERTY_NAME), 
                '*' . $query . '*');
            $or_conditions[] = new PatternMatchCondition(
                new PropertyConditionVariable(
                    \Chamilo\Core\Group\Storage\DataClass\Group :: class_name(), 
                    \Chamilo\Core\Group\Storage\DataClass\Group :: PROPERTY_DESCRIPTION), 
                '*' . $query . '*');
            $or_conditions[] = new PatternMatchCondition(
                new PropertyConditionVariable(
                    \Chamilo\Core\Group\Storage\DataClass\Group :: class_name(), 
                    \Chamilo\Core\Group\Storage\DataClass\Group :: PROPERTY_CODE), 
                '*' . $query . '*');
            return new OrCondition($or_conditions);
        }
        else
        {
            $condition = new EqualityCondition(
                new PropertyConditionVariable(
                    \Chamilo\Core\Group\Storage\DataClass\Group :: class_name(), 
                    \Chamilo\Core\Group\Storage\DataClass\Group :: PROPERTY_PARENT_ID), 
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
                    \Chamilo\Core\Group\Storage\DataClass\GroupRelUser :: class_name(), 
                    \Chamilo\Core\Group\Storage\DataClass\GroupRelUser :: PROPERTY_GROUP_ID), 
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
                new PropertyConditionVariable(
                    \Chamilo\Core\User\Storage\DataClass\User :: class_name(), 
                    \Chamilo\Core\User\Storage\DataClass\User :: PROPERTY_FIRSTNAME), 
                '*' . $query . '*');
            $pattern_conditions[] = new PatternMatchCondition(
                new PropertyConditionVariable(
                    \Chamilo\Core\User\Storage\DataClass\User :: class_name(), 
                    \Chamilo\Core\User\Storage\DataClass\User :: PROPERTY_LASTNAME), 
                '*' . $query . '*');
            $pattern_conditions[] = new PatternMatchCondition(
                new PropertyConditionVariable(
                    \Chamilo\Core\User\Storage\DataClass\User :: class_name(), 
                    \Chamilo\Core\User\Storage\DataClass\User :: PROPERTY_USERNAME), 
                '*' . $query . '*');
            $pattern_conditions[] = new PatternMatchCondition(
                new PropertyConditionVariable(
                    \Chamilo\Core\User\Storage\DataClass\User :: class_name(), 
                    \Chamilo\Core\User\Storage\DataClass\User :: PROPERTY_OFFICIAL_CODE), 
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
