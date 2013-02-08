<?php
namespace application\ehb_sync\bamaflex;

/**
 * @package ehb.sync;
 */

class StudentTrainingChoicesGroupSynchronization extends GroupSynchronization
{
    CONST IDENTIFIER = 'CH';

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
        return 'Keuzes';
    }

    function get_children()
    {
        $children = array();
        $children[] = GroupSynchronization :: factory('student_training_choices_graduations', $this);
        $children[] = GroupSynchronization :: factory('student_training_choices_options', $this);
        $children[] = GroupSynchronization :: factory('student_training_choices_combinations', $this);
        return $children;
    }
}
