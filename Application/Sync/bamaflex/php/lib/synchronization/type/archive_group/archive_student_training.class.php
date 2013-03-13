<?php
namespace application\ehb_sync\bamaflex;

/**
 *
 * @package ehb.sync;
 */
class ArchiveStudentTrainingGroupSynchronization extends ArchiveTrainingGroupSynchronization
{
    
    /*
     * (non-PHPdoc) @see application\ehb_sync\bamaflex.TrainingGroupSynchronization::get_group_type()
     */
    public function get_group_type()
    {
        return ArchiveUserTypeStudentGroupSynchronization :: IDENTIFIER;
    }

    public function get_user_official_codes()
    {
        $user_mails = array();
        $query = 'SELECT DISTINCT person_id FROM [dbo].[v_discovery_list_user_student_basic]  WHERE training_id = "' .
             $this->get_parameter(self :: RESULT_PROPERTY_TRAINING_ID) . '" AND type = 1 AND result != 8';
        $users = $this->get_result($query);
        
        while ($user = $users->next_result(false))
        {
            $user_mails[] = $user['person_id'];
        }
        return $user_mails;
    }
}
