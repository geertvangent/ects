<?php
namespace application\ehb_sync\bamaflex;

/**
 *
 * @package ehb.sync;
 */

class StudentTrainingTrajectoriesIndividualGroupSynchronization extends GroupSynchronization
{
    CONST IDENTIFIER = 'IN';

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
        return 'Individuele deeltrajecten';
    }

    function get_user_official_codes()
    {
        $user_mails = array();
        $training = $this->get_trajectory()->get_training()->get_parameter(TrainingGroupSynchronization :: RESULT_PROPERTY_TRAINING_ID);
        
        $query = 'SELECT DISTINCT person_id FROM [dbo].[v_discovery_list_user]  WHERE training_id = "' . $training . '" AND trajectory_type = 3 AND type = 1 AND result != 8';
        $users = $this->get_result($query);
        
        while ($user = $users->next_result(false))
        {
            $user_mails[] = $user['person_id'];
        }
        
        return $user_mails;
    }
}
?>