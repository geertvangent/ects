<?php
namespace Ehb\Application\Sync\Bamaflex\Synchronization\Type\Group;

use Ehb\Application\Sync\Bamaflex\Synchronization\Type\GroupSynchronization;

/**
 *
 * @package ehb.sync;
 */
class StudentTrainingChoicesGroupSynchronization extends GroupSynchronization
{
    CONST IDENTIFIER = 'CH';

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
        return 'Keuzes';
    }

    public function get_children()
    {
        $children = array();
        $children[] = GroupSynchronization :: factory('student_training_choices_graduations', $this);
        $children[] = GroupSynchronization :: factory('student_training_choices_options', $this);
        $children[] = GroupSynchronization :: factory('student_training_choices_combinations', $this);
        return $children;
    }
}
