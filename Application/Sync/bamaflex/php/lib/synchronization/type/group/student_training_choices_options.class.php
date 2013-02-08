<?php
namespace application\ehb_sync\bamaflex;

/**
 * @package ehb.sync;
 */

class StudentTrainingChoicesOptionsGroupSynchronization extends GroupSynchronization
{
    CONST IDENTIFIER = 'OP';

    function get_choices()
    {
        return $this->get_synchronization();
    }

    function get_code()
    {
        return $this->get_parent_group()->get_code() . '_' . self :: IDENTIFIER;
    }

    function get_name()
    {
        return 'Keuzeopties';
    }

    function get_children()
    {
        $query = 'SELECT * FROM [INFORDATSYNC].[dbo].[v_discovery_training_choice_basic] WHERE training_id = ' . $this->get_choices()->get_training()->get_parameter(TrainingGroupSynchronization :: RESULT_PROPERTY_TRAINING_ID);

        $options = $this->get_result($query);

        $children = array();
        while ($option = $options->next_result(false))
        {
            $children[] = GroupSynchronization :: factory('student_training_choices_option', $this, $option);
        }
        return $children;
    }
}
