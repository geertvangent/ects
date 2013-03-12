<?php
namespace application\ehb_sync\bamaflex;

/**
 *
 * @package ehb.sync;
 */



class ArchiveUserTypeGuestTeacherGroupSynchronization extends ArchiveGroupSynchronization
{
    CONST IDENTIFIER = 'GT';

    function get_department()
    {
        return $this->get_synchronization();
    }

    function get_code()
    {
        return $this->get_parent_group()->get_code() . '_' . self :: IDENTIFIER;
    }

    function get_name()
    {
        return 'Gastprofessoren';
    }

    function get_user_official_codes()
    {
        $user_mails = array();

        $query = 'SELECT DISTINCT person_id FROM [dbo].[v_discovery_list_user_guest_teacher]  WHERE faculty_id = ' . $this->get_department()->get_parameter(ArchiveDepartmentGroupSynchronization :: RESULT_PROPERTY_DEPARTMENT_ID) . '
                AND type = 4 AND date_start <= \''. $this->get_academic_year_end() .'\' AND (date_end >= \''. $this->get_academic_year_end() .'\' OR date_end is null)';
        $users = $this->get_result($query);

        while ($user = $users->next_result(false))
        {
            $user_mails[] = $user['person_id'];
        }

        return $user_mails;
    }
}
