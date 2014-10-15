<?php
namespace application\discovery\module\employment\implementation\bamaflex;

use application\discovery\module\profile\DataManager;

class Module extends \application\discovery\module\employment\Module
{

    public function get_unique_faculty($parts)
    {
        $faculties = array();
        foreach ($parts as $part)
        {
            if (! in_array($part->get_faculty(), $faculties))
            {
                $faculties[] = $part->get_faculty();
            }
        }
        if (count($faculties) > 1)
        {
            return $faculties;
        }
        else
        {
            return $faculties[0];
        }
    }

    public function get_unique_department($parts)
    {
        $departments = array();
        foreach ($parts as $part)
        {
            if (! in_array($part->get_department(), $departments))
            {
                $departments[] = $part->get_department();
            }
        }
        if (count($departments) > 1)
        {
            return $departments;
        }
        else
        {
            return $departments[0];
        }
    }

    public function get_employment_parts($employment_id)
    {
        return DataManager :: get_instance($this->get_module_instance())->retrieve_employment_parts($employment_id);
    }
}
