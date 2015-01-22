<?php
namespace Chamilo\Application\Discovery\Module\Photo\Implementation\Bamaflex;

use Chamilo\Core\Group\Storage\DataClass\Group;
use Chamilo\Libraries\Storage\Query\Condition\InCondition;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;

class Module extends \Chamilo\Application\Discovery\Module\Photo\Module
{

    public function get_groups()
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
                $group = \Chamilo\Core\Group\Storage\DataManager :: retrieve_group_by_code($code);
                
                if ($group instanceof Group)
                {
                    $groups[] = $group;
                }
            }
        }
        return $groups;
    }

    public function get_users()
    {
        $users = array();
        foreach ($this->get_groups() as $group)
        {
            $users = array_merge($users, $group->get_users(true, true));
        }
        
        return array_unique($users);
    }

    public function get_condition()
    {
        return new InCondition(
            new PropertyConditionVariable(\Chamilo\Core\User\Storage\DataClass\User :: class_name(), \Chamilo\Core\User\Storage\DataClass\User :: PROPERTY_ID), 
            $this->get_users());
    }
}
