<?php
namespace application\ehb_sync\bamaflex;

/**
 *
 * @package ehb.sync;
 */

use group\GroupDataManager;
use group\Group;

use common\libraries\AndCondition;
use common\libraries\EqualityCondition;

class UserTypeGuestTeacherGroupSynchronization extends GroupSynchronization
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

        $query = 'SELECT DISTINCT person_id FROM [dbo].[v_discovery_list_user_guest_teacher]  WHERE faculty_id IN (SELECT student_faculty_id FROM dbo.t_employee_faculties WHERE year > "2005-06" AND employee_faculty_id IN (SELECT employee_faculty_id FROM dbo.t_employee_faculties WHERE student_faculty_id = ' . $this->get_department()->get_parameter(DepartmentGroupSynchronization :: RESULT_PROPERTY_DEPARTMENT_ID) . ')
                AND type = 4 AND date_start <= current_timestamp AND (date_end >= current_timestamp OR date_end is null))';
        $users = $this->get_result($query);

        while ($user = $users->next_result(false))
        {
            $user_mails[] = $user['person_id'];
        }

        return $user_mails;
    }
}
?>