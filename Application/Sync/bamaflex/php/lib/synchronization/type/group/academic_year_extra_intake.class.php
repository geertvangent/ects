<?php
namespace application\ehb_sync\bamaflex;

/**
 * @package ehb.sync;
 */

use group\GroupDataManager;
use group\Group;

use common\libraries\AndCondition;
use common\libraries\EqualityCondition;

class AcademicYearExtraIntakeGroupSynchronization extends GroupSynchronization
{
    CONST IDENTIFIER = 'INT';

    function get_code()
    {
        return $this->get_parent_group()->get_code() . '_' . self :: IDENTIFIER;
    }

    function get_name()
    {
        return 'Instromende studenten';
    }

    function get_user_official_codes()
    {
        $query = 'EXEC [dbo].[sp_sync_students_intake] @academiejaar = N\'' . $this->get_academic_year() . '\'';
        $users = $this->get_result($query);

        $user_mails = array();
        while ($user = $users->next_result(false))
        {
            $user_mails[] = $user['p_persoon'];
        }
        return $user_mails;
    }
}
?>