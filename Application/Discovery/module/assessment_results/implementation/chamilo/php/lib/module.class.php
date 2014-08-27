<?php
namespace application\discovery\module\assessment_results\implementation\chamilo;

use libraries\AndCondition;
use libraries\Utilities;
use application\discovery\Parameters;
use libraries\OrCondition;
use libraries\PatternMatchCondition;
use libraries\EqualityCondition;
use libraries\Request;

class Module extends \application\discovery\module\assessment_results\Module
{

    public function get_group()
    {
        if (! $this->group)
        {
            $this->group = Request :: get(\core\group\GroupManager :: PARAM_GROUP_ID);

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
            $group = \core\group\DataManager :: get_instance()->retrieve_groups(
                new EqualityCondition(\core\group\Group :: PROPERTY_PARENT, 0))->next_result();
            $this->root_group = $group;
        }

        return $this->root_group;
    }

    public function get_subgroups_condition($query = null)
    {
        if (isset($query) && $query != '')
        {
            $or_conditions = array();
            $or_conditions[] = new PatternMatchCondition(\core\group\Group :: PROPERTY_NAME, '*' . $query . '*');
            $or_conditions[] = new PatternMatchCondition(\core\group\Group :: PROPERTY_DESCRIPTION, '*' . $query . '*');
            $or_conditions[] = new PatternMatchCondition(\core\group\Group :: PROPERTY_CODE, '*' . $query . '*');
            return new OrCondition($or_conditions);
        }
        else
        {
            $condition = new EqualityCondition(\core\group\Group :: PROPERTY_PARENT, $this->get_group());
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
            return new EqualityCondition(\core\group\GroupRelUser :: PROPERTY_GROUP_ID, $this->get_group());
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
            $pattern_conditions[] = new PatternMatchCondition(\core\user\User :: PROPERTY_FIRSTNAME, '*' . $query . '*');
            $pattern_conditions[] = new PatternMatchCondition(\core\user\User :: PROPERTY_LASTNAME, '*' . $query . '*');
            $pattern_conditions[] = new PatternMatchCondition(\core\user\User :: PROPERTY_USERNAME, '*' . $query . '*');
            $pattern_conditions[] = new PatternMatchCondition(
                \core\user\User :: PROPERTY_OFFICIAL_CODE,
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
