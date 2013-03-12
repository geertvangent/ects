<?php
namespace application\ehb_sync\bamaflex;

/**
 *
 * @package ehb.sync;
 */

class TeacherTrainingGroupSynchronization extends TrainingGroupSynchronization
{

    /*
     * (non-PHPdoc) @see
     * application\ehb_sync\bamaflex.TrainingGroupSynchronization::get_group_type()
     */
    function get_group_type()
    {
        return UserTypeTeacherGroupSynchronization :: IDENTIFIER;
    }

    function get_children()
    {
        $query = 'SELECT * FROM [INFORDATSYNC].[dbo].[v_discovery_course_basic] WHERE training_id = ' . $this->get_parameter(self :: RESULT_PROPERTY_TRAINING_ID) . ' AND parent_id IS NULL AND exchange = 0';
        $courses = $this->get_result($query);

        $children = array();
        while ($course = $courses->next_result(false))
        {
            $children[] = GroupSynchronization :: factory('teacher_course', $this, $course);
        }
        return $children;
    }
}
