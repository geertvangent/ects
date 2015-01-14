<?php
namespace Application\EhbSync\bamaflex\synchronization\type\course_category;

class DepartmentCourseCategorySynchronization extends CourseCategorySynchronization
{
    CONST IDENTIFIER = 'DEP';
    const RESULT_PROPERTY_ACADEMIC_YEAR = 'year';
    const RESULT_PROPERTY_DEPARTMENT = 'name';
    const RESULT_PROPERTY_DEPARTMENT_ID = 'id';

    public function get_code()
    {
        return self :: IDENTIFIER . '_' . $this->get_parameter(self :: RESULT_PROPERTY_DEPARTMENT_ID);
    }

    public function get_name()
    {
        return $this->get_parameter(self :: RESULT_PROPERTY_DEPARTMENT);
    }

    public function get_children()
    {
        $query = 'SELECT * FROM [INFORDATSYNC].[dbo].[v_discovery_training_basic] WHERE faculty_id = ' .
             $this->get_parameter(self :: RESULT_PROPERTY_DEPARTMENT_ID);
        
        $trainings = $this->get_result($query);
        
        $children = array();
        while ($training = $trainings->next_result(false))
        {
            $children[] = CourseCategorySynchronization :: factory('training', $this, $training);
        }
        return $children;
    }
}
