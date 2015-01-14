<?php
namespace Application\EhbSync\bamaflex\synchronization\type\group;

/**
 *
 * @package ehb.sync;
 */
class AcademicYearExtraIntakeGroupSynchronization extends GroupSynchronization
{
    CONST IDENTIFIER = 'INT';

    public function get_code()
    {
        return $this->get_parent_group()->get_code() . '_' . self :: IDENTIFIER;
    }

    public function get_name()
    {
        return 'Instromende studenten';
    }

    public function get_user_official_codes()
    {
        $query = 'SELECT DISTINCT id FROM [dbo].[v_discovery_profile_basic]  WHERE company_id LIKE CAST(LEFT(\'' .
             $this->get_synchronization()->get_synchronization()->get_synchronization()->get_year() . '\', 4) as VARCHAR) + \'%\'';
        $users = $this->get_result($query);

        $user_mails = array();
        while ($user = $users->next_result(false))
        {
            $user_mails[] = $user['id'];
        }

        return $user_mails;
    }
}
