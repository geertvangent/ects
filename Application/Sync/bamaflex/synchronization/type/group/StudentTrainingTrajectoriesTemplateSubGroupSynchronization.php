<?php
namespace Application\EhbSync\bamaflex\synchronization\type\group;

/**
 *
 * @package ehb.sync;
 */
class StudentTrainingTrajectoriesTemplateSubGroupSynchronization extends GroupSynchronization
{
    CONST IDENTIFIER = 'SU';
    const RESULT_PROPERTY_TRAJECTORY = 'name';
    const RESULT_PROPERTY_TRAJECTORY_ID = 'id';

    public function get_main_template()
    {
        return $this->get_synchronization();
    }

    public function get_code()
    {
        return $this->get_main_template()->get_current_group()->get_code() . '_' . self :: IDENTIFIER . '_' .
             $this->get_parameter(self :: RESULT_PROPERTY_TRAJECTORY_ID);
    }

    public function get_name()
    {
        return $this->get_parameter(self :: RESULT_PROPERTY_TRAJECTORY);
    }

    public function get_user_official_codes()
    {
        $user_mails = array();

        $query = 'SELECT DISTINCT person_id FROM [dbo].[v_discovery_list_user_student_basic]  WHERE trajectory_type = 1 AND type = 1 AND result != 8 AND trajectory_id = ' .
             $this->get_parameter(self :: RESULT_PROPERTY_TRAJECTORY_ID);
        $users = $this->get_result($query);

        while ($user = $users->next_result(false))
        {
            $user_mails[] = $user['person_id'];
        }

        return $user_mails;
    }
}
