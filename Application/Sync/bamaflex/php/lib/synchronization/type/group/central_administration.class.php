<?php
namespace application\ehb_sync\bamaflex;

/**
 * @package ehb.sync;
 */



class CentralAdministrationGroupSynchronization extends GroupSynchronization
{
    CONST IDENTIFIER = 'CA';

    public function get_code()
    {
        return self :: IDENTIFIER;
    }

    public function get_name()
    {
        return 'Centrale administratie';
    }

    public function get_user_official_codes()
    {
        $user_mails = array();

        $query = 'SELECT DISTINCT person_id FROM [dbo].[v_discovery_list_user_employee]  WHERE year = "' . $this->get_academic_year() . '" AND faculty_id = 0 AND type = 3';
        $users = $this->get_result($query);

        while ($user = $users->next_result(false))
        {
            $user_mails[] = $user['person_id'];
        }

        return $user_mails;
    }
}
