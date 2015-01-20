<?php
namespace Ehb\Application\Sync\Bamaflex\Synchronization\Type\Group;

use Ehb\Application\Sync\Bamaflex\Synchronization\Type\GroupSynchronization;
/**
 *
 * @package ehb.sync;
 */
class StudentTrainingTrajectoriesPartGroupSynchronization extends GroupSynchronization
{
    CONST IDENTIFIER = 'PA';
    const RESULT_PROPERTY_TRAJECTORY_PART = 'trajectory_part';

    public function get_part()
    {
        return $this->get_synchronization();
    }

    public function get_code()
    {
        return $this->get_part()->get_current_group()->get_code() . '_' .
             $this->get_parameter(self :: RESULT_PROPERTY_TRAJECTORY_PART);
    }

    public function get_name()
    {
        return $this->get_parameter(self :: RESULT_PROPERTY_TRAJECTORY_PART);
    }

    public function get_user_official_codes()
    {
        $user_mails = array();

        $query = 'SELECT DISTINCT person_id FROM [dbo].[v_discovery_list_user_student_basic]  WHERE type = 1 AND result != 8 AND training_id = ' .
             $this->get_part()->get_trajectory()->get_training()->get_parameter(
                TrainingGroupSynchronization :: RESULT_PROPERTY_TRAINING_ID) . ' AND trajectory_part = ' .
             $this->get_parameter(self :: RESULT_PROPERTY_TRAJECTORY_PART);

        $users = $this->get_result($query);

        while ($user = $users->next_result(false))
        {
            $user_mails[] = $user['person_id'];
        }

        return $user_mails;
    }
}
