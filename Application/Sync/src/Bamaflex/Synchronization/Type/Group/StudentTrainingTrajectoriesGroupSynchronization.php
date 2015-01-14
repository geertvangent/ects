<?php
namespace Chamilo\Application\EhbSync\Bamaflex\Synchronization\Type\Group;

/**
 *
 * @package ehb.sync;
 */
class StudentTrainingTrajectoriesGroupSynchronization extends GroupSynchronization
{
    CONST IDENTIFIER = 'TR';

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
        return 'Trajecten';
    }

    public function get_children()
    {
        $children = array();
        $children[] = GroupSynchronization :: factory('student_training_trajectories_template', $this);
        $children[] = GroupSynchronization :: factory('student_training_trajectories_personal', $this);
        $children[] = GroupSynchronization :: factory('student_training_trajectories_individual', $this);
        $children[] = GroupSynchronization :: factory('student_training_trajectories_unknown', $this);
        $children[] = GroupSynchronization :: factory('student_training_trajectories_parts', $this);
        return $children;
    }
}
