<?php
namespace application\ehb_sync\bamaflex;

/**
 * @package ehb.sync;
 */

class StudentTrainingChoicesCombinationsGroupSynchronization extends GroupSynchronization
{
    CONST IDENTIFIER = 'CO';

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
        return 'Keuzerichtingen';
    }

    function get_children()
    {
        $query = 'EXEC [dbo].[sp_structure_training_choice_combinations] @p_opleiding = ' . $this->get_choices()->get_training()->get_parameter(TrainingGroupSynchronization :: RESULT_PROPERTY_TRAINING_ID);
        $combinations = $this->get_result($query);

        $children = array();
        while ($combination = $combinations->next_result(false))
        {
            $children[] = GroupSynchronization :: factory('student_training_choices_combination', $this, $combination);
        }
        return $children;
    }
}
?>