<?php
namespace Application\EhbSync\bamaflex\synchronization\type\group;

/**
 *
 * @package ehb.sync;
 */
class StudentTrainingChoicesCombinationsGroupSynchronization extends GroupSynchronization
{
    CONST IDENTIFIER = 'CO';

    public function get_choices()
    {
        return $this->get_synchronization();
    }

    public function get_code()
    {
        return $this->get_parent_group()->get_code() . '_' . self :: IDENTIFIER;
    }

    public function get_name()
    {
        return 'Keuzerichtingen';
    }

    public function get_children()
    {
        $query = 'SELECT * FROM [INFORDATSYNC].[dbo].[v_discovery_training_choice_option_basic] WHERE training_id = ' .
             $this->get_choices()->get_training()->get_parameter(
                TrainingGroupSynchronization :: RESULT_PROPERTY_TRAINING_ID);
        
        $combinations = $this->get_result($query);
        
        $children = array();
        while ($combination = $combinations->next_result(false))
        {
            $children[] = GroupSynchronization :: factory('student_training_choices_combination', $this, $combination);
        }
        return $children;
    }
}
