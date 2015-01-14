<?php
namespace Chamilo\Application\EhbSync\Bamaflex\Synchronization\Type\Group;

use Chamilo\Application\EhbSync\Bamaflex\Synchronization\Type\GroupSynchronization;
/**
 *
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
        $academic_year = $this->get_academic_year();
        $academic_year = explode(',', $academic_year);

        $user_mails = array();

        $query = 'SELECT DISTINCT person_id FROM [dbo].[v_discovery_list_user_employee]  WHERE year = \'' .
             $academic_year[0] . '\' AND faculty_id = 0 AND type = 3 AND (date_end >= \'' .
             date('Y-m-d', strtotime('-2 months')) . '\' OR date_end IS NULL)';
        $users = $this->get_result($query);

        while ($user = $users->next_result(false))
        {
            $user_mails[] = $user['person_id'];
        }

        return $user_mails;
    }
}
