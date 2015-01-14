<?php
namespace Chamilo\Application\EhbSync\Bamaflex\Synchronization\Type\Group;

/**
 *
 * @package ehb.sync;
 */
class StudentTrainingTrajectoriesPartsGroupSynchronization extends GroupSynchronization
{
    CONST IDENTIFIER = 'PA';

    public function get_trajectory()
    {
        return $this->get_synchronization();
    }

    public function get_code()
    {
        return $this->get_parent_group()->get_code() . '_' . self :: IDENTIFIER;
    }

    public function get_name()
    {
        return 'Trajectschijven';
    }

    public function get_children()
    {
        $query = 'SELECT DISTINCT trajectory_part FROM [INFORDATSYNC].[dbo].[v_discovery_course_basic] WHERE exchange = 0 AND training_id = ' .
             $this->get_trajectory()->get_training()->get_parameter(
                TrainingGroupSynchronization :: RESULT_PROPERTY_TRAINING_ID);
        
        $trajectory_parts = $this->get_result($query);
        
        $children = array();
        while ($trajectory_part = $trajectory_parts->next_result(false))
        {
            $children[] = GroupSynchronization :: factory('student_training_trajectories_part', $this, $trajectory_part);
        }
        return $children;
    }
}
