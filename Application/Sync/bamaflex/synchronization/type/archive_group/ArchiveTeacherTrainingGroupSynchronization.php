<?php
namespace Application\EhbSync\bamaflex\synchronization\type\archive_group;

/**
 *
 * @package ehb.sync;
 */
class ArchiveTeacherTrainingGroupSynchronization extends ArchiveTrainingGroupSynchronization
{

    /*
     * (non-PHPdoc) @see application\ehb_sync\bamaflex.TrainingGroupSynchronization::get_group_type()
     */
    public function get_group_type()
    {
        return ArchiveUserTypeTeacherGroupSynchronization :: IDENTIFIER;
    }

    public function get_user_official_codes()
    {
        $user_mails = array();

        if (! $this->is_old())
        {
            $query = 'SELECT DISTINCT person_id FROM [dbo].[v_discovery_list_user_teacher_basic]  WHERE training_id = "' .
                 $this->get_parameter(self :: RESULT_PROPERTY_TRAINING_ID) . '" AND type = 2';
            $users = $this->get_result($query);

            while ($user = $users->next_result(false))
            {
                $user_mails[] = $user['person_id'];
            }
        }

        return $user_mails;
    }
}
