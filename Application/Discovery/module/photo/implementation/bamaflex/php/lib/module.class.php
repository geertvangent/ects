<?php
namespace application\discovery\module\photo\implementation\bamaflex;

use common\libraries\Translation;
use common\libraries\Breadcrumb;
use common\libraries\BreadcrumbTrail;
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
            $faculty = DataManager :: get_instance($this->get_module_instance())->retrieve_faculty(
                    $parameters->get_faculty_id());
            BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $faculty->get_year()));
            BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $faculty->get_name()));
            
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
                        BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, Translation :: get('Teachers')));
                        break;
                    case self :: TYPE_STUDENT :
                        $codes[] = 'DEP_' . $parameters->get_faculty_id() . '_STU';
                        BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, Translation :: get('Students')));
                        break;
                    case self :: TYPE_EMPLOYEE :
                        $codes[] = 'DEP_' . $parameters->get_faculty_id() . '_ATP';
                        BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, Translation :: get('Employees')));
                        break;
                }
            }
        }
        elseif ($parameters->get_training_id())
        {
            $training = DataManager :: get_instance($this->get_module_instance())->retrieve_training(
                    $parameters->get_training_id());
            BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $training->get_year()));
            BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $training->get_faculty()));
            BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $training->get_name()));
            
            if ($parameters->get_type())
            {
                switch ($parameters->get_type())
                {
                    case self :: TYPE_TEACHER :
                        $codes[] = 'TRA_OP_' . $parameters->get_training_id();
                        BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, Translation :: get('Teachers')));
                        break;
                    case self :: TYPE_STUDENT :
                        $codes[] = 'TRA_STU_' . $parameters->get_training_id();
                        BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, Translation :: get('Students')));
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
            $programme = DataManager :: get_instance($this->get_module_instance())->retrieve_programme(
                    $parameters->get_programme_id());
            BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $programme->get_year()));
            BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $programme->get_faculty()));
            BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $programme->get_training()));
            BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, $programme->get_name()));
            
            if ($parameters->get_type())
            {
                switch ($parameters->get_type())
                {
                    case self :: TYPE_TEACHER :
                        $codes[] = 'COU_OP_' . $parameters->get_programme_id();
                        BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, Translation :: get('Teachers')));
                        break;
                    case self :: TYPE_STUDENT :
                        $codes[] = 'COU_STU_' . $parameters->get_programme_id();
                        BreadcrumbTrail :: get_instance()->add(new Breadcrumb(null, Translation :: get('Students')));
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

    function get_context()
    {
    }
}
?>