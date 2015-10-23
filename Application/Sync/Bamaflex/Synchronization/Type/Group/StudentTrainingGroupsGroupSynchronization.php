<?php
namespace Ehb\Application\Sync\Bamaflex\Synchronization\Type\Group;

use Ehb\Application\Sync\Bamaflex\Synchronization\Type\GroupSynchronization;

/**
 *
 * @package ehb.sync;
 */
class StudentTrainingGroupsGroupSynchronization extends GroupSynchronization
{
    CONST IDENTIFIER = 'GR';

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
        return 'Klasgroepen';
    }

    public function get_children()
    {
        $query = 'SELECT * FROM [INFORDATSYNC].[dbo].[v_discovery_group_advanced] WHERE source = 1 AND type != 1 AND training_id = ' .
             $this->get_training()->get_parameter(TrainingGroupSynchronization :: RESULT_PROPERTY_TRAINING_ID);
        $groups = $this->get_result($query);

        $children = array();
        while ($group = $groups->next_result(false))
        {
            $children[] = GroupSynchronization :: factory('student_group', $this, $group);
        }
        return $children;
    }
}
