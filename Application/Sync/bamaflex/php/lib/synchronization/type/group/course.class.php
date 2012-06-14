<?php
namespace application\ehb_sync\bamaflex;

/**
 * @package ehb.sync;
 */

use common\libraries\Utilities;

abstract class CourseGroupSynchronization extends GroupSynchronization
{
    CONST IDENTIFIER = 'COU';

    const RESULT_PROPERTY_COURSE = 'rapportonderdeel';
    const RESULT_PROPERTY_COURSE_ID = 'programma_id';
    const RESULT_PROPERTY_PARENT_ID = 'parent_programma_id';
    const RESULT_PROPERTY_HAS_CHILDREN = 'has_children';

    function get_code()
    {
        return $this :: IDENTIFIER . '_' . $this->get_parameter(self :: RESULT_PROPERTY_COURSE_ID);
    }

    function get_name()
    {
        return $this->get_parameter(self :: RESULT_PROPERTY_COURSE);
    }

    abstract function get_department_id();

    abstract function get_training_id();

    abstract function get_group_type();

    function get_children()
    {
        $children = array();

        if (is_null($this->get_parameter(self :: RESULT_PROPERTY_PARENT_ID)) && $this->get_parameter(self :: RESULT_PROPERTY_HAS_CHILDREN) == 1)
        {
            $query = 'EXEC [dbo].[sp_structure_courses_children] @academiejaar = N\'' . $this->get_academic_year() . '\', @departement_id = ' . $this->get_department_id() . ', @opleiding_id = ' . $this->get_training_id() . ', @programma_id = ' . $this->get_parameter(self :: RESULT_PROPERTY_COURSE_ID);

            $courses = $this->get_result($query);

            while ($course = $courses->next_result(false))
            {
                $children[] = GroupSynchronization :: factory($this->get_group_type(), $this, $course);
            }
        }

        return $children;
    }
}
?>