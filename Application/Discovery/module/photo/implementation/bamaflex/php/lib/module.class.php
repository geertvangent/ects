<?php
namespace application\discovery\module\photo\implementation\bamaflex;

use common\libraries\InCondition;
use application\discovery\module\photo\DataManager;
use common\libraries\Request;
use application\discovery\DiscoveryManager;
use user\UserDataManager;
use group\Group;
use group\GroupDataManager;

class Module extends \application\discovery\module\photo\Module
{

    function get_groups()
    {
        $parameters = $this->get_module_parameters();
        $codes = array();

        if ($parameters->get_faculty_id())
        {
            if (! $parameters->get_type())
            {
                $codes[] = 'DEP_' . $parameters->get_faculty_id();
            }
            else
            {
                switch ($parameters->get_type())
                {
                    case self :: TYPE_TEACHER :
                        $codes[] = 'DEP_' . $parameters->get_faculty_id() . '_OP';
                        break;
                    case self :: TYPE_STUDENT :
                        $codes[] = 'DEP_' . $parameters->get_faculty_id() . '_STU';
                        break;
                    case self :: TYPE_EMPLOYEE :
                        $codes[] = 'DEP_' . $parameters->get_faculty_id() . '_ATP';
                        break;
                }
            }
        }
        elseif ($parameters->get_training_id())
        {
            if ($parameters->get_type())
            {
                switch ($parameters->get_type())
                {
                    case self :: TYPE_TEACHER :
                        $codes[] = 'TRA_OP_' . $parameters->get_training_id();
                        break;
                    case self :: TYPE_STUDENT :
                        $codes[] = 'TRA_STU_' . $parameters->get_training_id();
                        break;
                }
            }
            else
            {
                $codes[] = 'TRA_OP_' . $parameters->get_training_id();
                $codes[] = 'TRA_STU_' . $parameters->get_training_id();
            }
        }
        elseif ($parameters->get_programme_id())
        {
            if ($parameters->get_type())
            {
                switch ($parameters->get_type())
                {
                    case self :: TYPE_TEACHER :
                        $codes[] = 'COU_OP_' . $parameters->get_programme_id();
                        break;
                    case self :: TYPE_STUDENT :
                        $codes[] = 'COU_STU_' . $parameters->get_programme_id();
                        break;
                }
            }
            else
            {
                $codes[] = 'COU_OP_' . $parameters->get_programme_id();
                $codes[] = 'COU_STU_' . $parameters->get_programme_id();
            }
        }
        $groups = array();

        if (count($codes) > 0)
        {
            foreach ($codes as $code)
            {
                $group = GroupDataManager :: retrieve_group_by_code($code);

                if ($group instanceof Group)
                {
                    $groups[] = $group;
                }
            }
        }
        return $groups;
    }

    function get_users()
    {
        $users = array();
        foreach ($this->get_groups() as $group)
        {
            $users = array_merge($users, $group->get_users(true, true));
        }

        return array_unique($users);
    }

    function get_condition()
    {
        return new InCondition(\user\User :: PROPERTY_ID, $this->get_users());
    }
}
?>