<?php
namespace application\discovery\module\person\implementation\chamilo;

use common\libraries\AndCondition;
use common\libraries\Utilities;
use application\discovery\Parameters;
use common\libraries\OrCondition;
use common\libraries\PatternMatchCondition;
use common\libraries\EqualityCondition;
use common\libraries\Request;

class Module extends \application\discovery\module\person\Module
{

    function get_group()
    {
        if (! $this->group)
        {
            $this->group = Request :: get(\group\GroupManager :: PARAM_GROUP_ID);

            if (! $this->group)
            {
                $this->group = $this->get_root_group()->get_id();
            }
        }

        return $this->group;
    }

    function get_root_group()
    {
        if (! $this->root_group)
        {
            $group = \group\GroupDataManager :: get_instance()->retrieve_groups(
                    new EqualityCondition(\group\Group :: PROPERTY_PARENT, 0))->next_result();
            $this->root_group = $group;
        }

        return $this->root_group;
    }

    function get_subgroups_condition($query = null)
    {
        if (isset($query) && $query != '')
        {
            $or_conditions = array();
            $or_conditions[] = new PatternMatchCondition(\group\Group :: PROPERTY_NAME, '*' . $query . '*');
            $or_conditions[] = new PatternMatchCondition(\group\Group :: PROPERTY_DESCRIPTION, '*' . $query . '*');
            $or_conditions[] = new PatternMatchCondition(\group\Group :: PROPERTY_CODE, '*' . $query . '*');
            return new OrCondition($or_conditions);
        }
        else
        {
            $condition = new EqualityCondition(\group\Group :: PROPERTY_PARENT, $this->get_group());
        }

        return $condition;
    }

    function get_users_condition($query = null)
    {
        if (isset($query) && $query != '')
        {
            return self :: query_to_condition($query);
        }
        else
        {
            return new EqualityCondition(\group\GroupRelUser :: PROPERTY_GROUP_ID, $this->get_group());
        }
    }

    static function query_to_condition($query)
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
            $pattern_conditions[] = new PatternMatchCondition(\user\User :: PROPERTY_FIRSTNAME, '*' . $query . '*');
            $pattern_conditions[] = new PatternMatchCondition(\user\User :: PROPERTY_LASTNAME, '*' . $query . '*');
            $pattern_conditions[] = new PatternMatchCondition(\user\User :: PROPERTY_USERNAME, '*' . $query . '*');
            $pattern_conditions[] = new PatternMatchCondition(\user\User :: PROPERTY_OFFICIAL_CODE, '*' . $query . '*');

            $conditions[] = new OrCondition($pattern_conditions);
        }

        return new AndCondition($conditions);
    }

    static function module_parameters()
    {
        return new Parameters();
    }
}
