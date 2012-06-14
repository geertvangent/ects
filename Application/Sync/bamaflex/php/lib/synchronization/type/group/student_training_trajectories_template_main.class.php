<?php
namespace application\ehb_sync\bamaflex;

/**
 * @package ehb.sync;
 */

class StudentTrainingTrajectoriesTemplateMainGroupSynchronization extends GroupSynchronization
{
    CONST IDENTIFIER = 'MA';

    const RESULT_PROPERTY_TRAJECTORY = 'opleidingstraject';
    const RESULT_PROPERTY_TRAJECTORY_ID = 'opleidingstraject_id';

    function get_template()
    {
        return $this->get_synchronization();
    }

    function get_code()
    {
        return $this->get_parent_group()->get_code() . '_' . self :: IDENTIFIER . '_' . $this->get_parameter(self :: RESULT_PROPERTY_TRAJECTORY_ID);
    }

    function get_name()
    {
        return $this->get_parameter(self :: RESULT_PROPERTY_TRAJECTORY);
    }

    function get_children()
    {
        $department = $this->get_template()->get_trajectory()->get_training()->get_user_type()->get_department()->get_parameter(DepartmentGroupSynchronization :: RESULT_PROPERTY_DEPARTMENT_ID);
        $training = $this->get_template()->get_trajectory()->get_training()->get_parameter(TrainingGroupSynchronization :: RESULT_PROPERTY_TRAINING_ID);
        $trajectory = $this->get_parameter(self :: RESULT_PROPERTY_TRAJECTORY_ID);

        $query = 'EXEC [dbo].[sp_structure_sub_trajectories] @academiejaar = N\'' . $this->get_academic_year() . '\', @departement_id = ' . $department . ', @opleiding_id = ' . $training . ', @opleidingstraject_id = ' . $trajectory;
        $trajectories = $this->get_result($query);

        $children = array();
        while ($trajectory = $trajectories->next_result(false))
        {
            $children[] = GroupSynchronization :: factory('student_training_trajectories_template_sub', $this, $trajectory);
        }
        return $children;
    }
}
?>