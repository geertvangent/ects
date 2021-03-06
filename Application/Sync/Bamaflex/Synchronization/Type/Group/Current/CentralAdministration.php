<?php
namespace Ehb\Application\Sync\Bamaflex\Synchronization\Type\Group\Current;

use Ehb\Application\Sync\Bamaflex\Synchronization\Type\GroupSynchronization;

/**
 *
 * @package ehb.sync;
 */
class CentralAdministration extends GroupSynchronization
{
    CONST IDENTIFIER = 'CUR_FAC_CA';

    public function get_code()
    {
        return self::IDENTIFIER;
    }

    public function get_name()
    {
        return 'Centrale administratie';
    }

    public function get_description()
    {
        return 'Opgelet! De gebruikers in deze groep worden over alle academiejaren heen geactualiseerd!';
    }

    public function get_user_official_codes()
    {
        $user_mails = array();
        
        $query = 'SELECT DISTINCT person_id FROM [dbo].[v_sync_current_employee] WHERE faculty_code = \'ADM\' AND (date_end >= \'' .
             date('Y-m-d', strtotime('-2 months')) . '\' OR date_end IS NULL)';
        $users = $this->get_result($query);
        
        while ($user = $users->next_result(false))
        {
            $user_mails[] = $user['person_id'];
        }
        
        return $user_mails;
    }
}
