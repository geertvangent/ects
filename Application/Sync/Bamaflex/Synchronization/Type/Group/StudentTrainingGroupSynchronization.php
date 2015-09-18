<?php
namespace Ehb\Application\Sync\Bamaflex\Synchronization\Type\Group;

use Ehb\Application\Sync\Bamaflex\Synchronization\Type\GroupSynchronization;

/**
 *
 * @package ehb.sync;
 */
class StudentTrainingGroupSynchronization extends TrainingGroupSynchronization
{

    /*
     * (non-PHPdoc) @see application\ehb_sync\bamaflex.TrainingGroupSynchronization::get_group_type()
     */
    public function get_group_type()
    {
        return UserTypeStudentGroupSynchronization :: IDENTIFIER;
    }

    public function get_children()
    {
        $children = array();
        $children[] = GroupSynchronization :: factory('student_training_trajectories', $this);
        $children[] = GroupSynchronization :: factory('student_training_choices', $this);
        $children[] = GroupSynchronization :: factory('student_training_courses', $this);
        $children[] = GroupSynchronization :: factory('student_training_groups', $this);
        return $children;
    }
}
