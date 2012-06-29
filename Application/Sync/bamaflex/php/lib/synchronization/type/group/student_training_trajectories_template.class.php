<?php
namespace application\ehb_sync\bamaflex;

/**
 *
 * @package ehb.sync;
 */

class StudentTrainingTrajectoriesTemplateGroupSynchronization extends GroupSynchronization
{
    CONST IDENTIFIER = 'TE';

    function get_trajectory()
    {
        return $this->get_synchronization();
    }

    function get_code()
    {
        return $this->get_parent_group()->get_code() . '_' . self :: IDENTIFIER;
    }

    function get_name()
    {
        return 'Modeltrajecten';
    }

    function get_children()
    {
        $query = 'SELECT * FROM [INFORDATSYNC].[dbo].[v_discovery_training_trajectory_basic] WHERE training_id = ' . $this->get_trajectory()->get_training()->get_parameter(TrainingGroupSynchronization :: RESULT_PROPERTY_TRAINING_ID);
        
        $trajectories = $this->get_result($query);
        
        $children = array();
        while ($trajectory = $trajectories->next_result(false))
        {
            $children[] = GroupSynchronization :: factory('student_training_trajectories_template_main', $this, $trajectory);
        }
        return $children;
    }
}
?>