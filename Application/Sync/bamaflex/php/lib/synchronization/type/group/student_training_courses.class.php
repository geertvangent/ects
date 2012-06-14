<?php
namespace application\ehb_sync\bamaflex;

/**
 * @package ehb.sync;
 */

class StudentTrainingCoursesGroupSynchronization extends GroupSynchronization
{
    CONST IDENTIFIER = 'CO';

    function get_training()
    {
        return $this->get_synchronization();
    }

    function get_code()
    {
        return $this->get_parent_group()->get_code() . '_' . self :: IDENTIFIER;
    }

    function get_name()
    {
        return 'Opleidingsonderdelen';
    }

    function get_children()
    {
        $query = 'EXEC [dbo].[sp_structure_courses_parents] @academiejaar = N\'' . $this->get_academic_year() . '\', @departement_id = ' . $this->get_training()->get_department_id() . ', @opleiding_id = ' . $this->get_training()->get_parameter(TrainingGroupSynchronization :: RESULT_PROPERTY_TRAINING_ID);
        $courses = $this->get_result($query);

        $children = array();
        while ($course = $courses->next_result(false))
        {
            $children[] = GroupSynchronization :: factory('student_course', $this, $course);
        }
        return $children;
    }
}
?>