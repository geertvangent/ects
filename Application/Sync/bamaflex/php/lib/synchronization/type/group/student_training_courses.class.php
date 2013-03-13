<?php
namespace application\ehb_sync\bamaflex;

/**
 *
 * @package ehb.sync;
 */
class StudentTrainingCoursesGroupSynchronization extends GroupSynchronization
{
    CONST IDENTIFIER = 'CO';

    public function get_training()
    {
        return $this->get_synchronization();
    }

    public function get_code()
    {
        return $this->get_parent_group()->get_code() . '_' . self :: IDENTIFIER;
    }

    public function get_name()
    {
        return 'Opleidingsonderdelen';
    }

    public function get_children()
    {
        $query = 'SELECT * FROM [INFORDATSYNC].[dbo].[v_discovery_course_basic] WHERE training_id = ' .
             $this->get_training()->get_parameter(TrainingGroupSynchronization :: RESULT_PROPERTY_TRAINING_ID) .
             ' AND parent_id IS NULL AND exchange = 0';
        $courses = $this->get_result($query);
        
        $children = array();
        while ($course = $courses->next_result(false))
        {
            $children[] = GroupSynchronization :: factory('student_course', $this, $course);
        }
        return $children;
    }
}
