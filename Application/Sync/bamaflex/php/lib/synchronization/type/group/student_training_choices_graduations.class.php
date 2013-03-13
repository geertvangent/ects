<?php
namespace application\ehb_sync\bamaflex;

/**
 *
 * @package ehb.sync;
 */
class StudentTrainingChoicesGraduationsGroupSynchronization extends GroupSynchronization
{
    CONST IDENTIFIER = 'GR';

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
        return 'Afstudeerrichtingen';
    }

    public function get_children()
    {
        $query = 'SELECT * FROM [INFORDATSYNC].[dbo].[v_discovery_training_major_basic] WHERE training_id = ' .
             $this->get_choices()->get_training()->get_parameter(
                TrainingGroupSynchronization :: RESULT_PROPERTY_TRAINING_ID);
        $graduations = $this->get_result($query);
        
        $children = array();
        while ($graduation = $graduations->next_result(false))
        {
            $children[] = GroupSynchronization :: factory('student_training_choices_graduation', $this, $graduation);
        }
        return $children;
    }
}
