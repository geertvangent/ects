<?php
namespace Chamilo\Application\EhbSync\Bamaflex\Synchronization\Type\Group;

use Chamilo\Application\EhbSync\Bamaflex\Synchronization\Type\GroupSynchronization;
/**
 *
 * @package ehb.sync;
 */
class StudentTrainingTrajectoriesPersonalGroupSynchronization extends GroupSynchronization
{
    CONST IDENTIFIER = 'PE';

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
        return 'Persoonlijke deeltrajecten';
    }

    public function get_user_official_codes()
    {
        $user_mails = array();
        $training = $this->get_trajectory()->get_training()->get_parameter(
            TrainingGroupSynchronization :: RESULT_PROPERTY_TRAINING_ID);

        $query = 'SELECT DISTINCT person_id FROM [dbo].[v_discovery_list_user_student_basic]  WHERE training_id = ' .
             $training . ' AND trajectory_type = 2 AND type = 1 AND result != 8';
        $users = $this->get_result($query);

        while ($user = $users->next_result(false))
        {
            $user_mails[] = $user['person_id'];
        }

        return $user_mails;
    }
}
