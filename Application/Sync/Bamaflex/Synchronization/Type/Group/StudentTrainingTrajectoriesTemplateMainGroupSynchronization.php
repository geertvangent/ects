<?php
namespace Chamilo\Application\EhbSync\Bamaflex\Synchronization\Type\Group;

use Chamilo\Application\EhbSync\Bamaflex\Synchronization\Type\GroupSynchronization;
/**
 *
 * @package ehb.sync;
 */
class StudentTrainingTrajectoriesTemplateMainGroupSynchronization extends GroupSynchronization
{
    CONST IDENTIFIER = 'MA';
    const RESULT_PROPERTY_TRAJECTORY = 'name';
    const RESULT_PROPERTY_TRAJECTORY_ID = 'id';

    public function get_template()
    {
        return $this->get_synchronization();
    }

    public function get_code()
    {
        return $this->get_parent_group()->get_code() . '_' . self :: IDENTIFIER . '_' .
             $this->get_parameter(self :: RESULT_PROPERTY_TRAJECTORY_ID);
    }

    public function get_name()
    {
        return $this->get_parameter(self :: RESULT_PROPERTY_TRAJECTORY);
    }

    public function get_children()
    {
        $trajectory = $this->get_parameter(self :: RESULT_PROPERTY_TRAJECTORY_ID);

        $query = 'SELECT * FROM [INFORDATSYNC].[dbo].[v_discovery_training_sub_trajectory_basic] WHERE trajectory_id = ' .
             $trajectory;

        $trajectories = $this->get_result($query);

        $children = array();
        while ($trajectory = $trajectories->next_result(false))
        {
            $children[] = GroupSynchronization :: factory(
                'student_training_trajectories_template_sub',
                $this,
                $trajectory);
        }
        return $children;
    }
}
