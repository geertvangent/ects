<?php
namespace application\ehb_sync\bamaflex;

/**
 *
 * @package ehb.sync;
 */

class StudentTrainingGroupSynchronization extends TrainingGroupSynchronization
{

    /*
     * (non-PHPdoc) @see
     * application\ehb_sync\bamaflex.TrainingGroupSynchronization::get_group_type()
     */
    function get_group_type()
    {
        return UserTypeStudentGroupSynchronization :: IDENTIFIER;
    }

    function get_children()
    {
        $children = array();
        $children[] = GroupSynchronization :: factory('student_training_trajectories', $this);
        $children[] = GroupSynchronization :: factory('student_training_choices', $this);
        $children[] = GroupSynchronization :: factory('student_training_courses', $this);
        return $children;
    }
}
