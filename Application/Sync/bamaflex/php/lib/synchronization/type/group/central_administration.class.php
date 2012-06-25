<?php
namespace application\ehb_sync\bamaflex;

/**
 * @package ehb.sync;
 */

use group\GroupDataManager;
use group\Group;

use common\libraries\AndCondition;
use common\libraries\EqualityCondition;

class CentralAdministrationGroupSynchronization extends GroupSynchronization
{
    CONST IDENTIFIER = 'CA';

    function get_code()
    {
        return self :: IDENTIFIER;
    }

    function get_name()
    {
        return 'Centrale administratie';
    }

    function get_user_official_codes()
    {
        $user_mails = array();

        $query = 'SELECT DISTINCT person_id FROM [dbo].[v_discovery_list_user]  WHERE year = "' . $this->get_academic_year() . '" AND faculty_id = 0 AND type = 3';
        $users = $this->get_result($query);

        while ($user = $users->next_result(false))
        {
            $user_mails[] = $user['person_id'];
        }

        return $user_mails;
    }
}
?>