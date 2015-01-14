<?php
namespace application\ehb_sync\bamaflex;

class AcademicYearCourseCategorySynchronization extends CourseCategorySynchronization
{
    CONST IDENTIFIER = 'AY';

    public function get_code()
    {
        return self :: IDENTIFIER . '_' . $this->get_synchronization()->get_year();
    }

    public function get_name()
    {
        return $this->get_synchronization()->get_year();
    }

    public function get_children()
    {
        $query = 'SELECT * FROM [INFORDATSYNC].[dbo].[v_discovery_faculty_basic] WHERE year = \'' .
             $this->get_synchronization()->get_year() . '\'';
        $departments = $this->get_result($query);

        $children = array();
        while ($department = $departments->next_result())
        {
            $children[] = CourseCategorySynchronization :: factory('department', $this, $department);
        }

        $query = 'SELECT * FROM [INFORDATSYNC].[dbo].[v_discovery_training_basic] WHERE year = \'' .
             $this->get_synchronization()->get_year() . '\' AND faculty_id is NULL';
        $trainings = $this->get_result($query);

        while ($training = $trainings->next_result())
        {
            $children[] = CourseCategorySynchronization :: factory('training', $this, $training);
        }

        return $children;
    }
}
