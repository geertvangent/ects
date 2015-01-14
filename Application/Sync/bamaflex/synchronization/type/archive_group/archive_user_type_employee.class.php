<?php
namespace Application\EhbSync\bamaflex\synchronization\type\archive_group;

/**
 *
 * @package ehb.sync;
 */
class ArchiveUserTypeEmployeeGroupSynchronization extends ArchiveGroupSynchronization
{
    CONST IDENTIFIER = 'ATP';

    public function get_department()
    {
        return $this->get_synchronization();
    }

    public function get_code()
    {
        return $this->get_parent_group()->get_code() . '_' . self :: IDENTIFIER;
    }

    public function get_name()
    {
        return 'Administratief en technisch personeel';
    }

    public function get_user_official_codes()
    {
        $user_mails = array();

        if (! $this->is_old())
        {
            $query = 'SELECT DISTINCT person_id FROM [dbo].[v_discovery_list_user_employee]  WHERE faculty_id = ' .
                 $this->get_department()->get_parameter(DepartmentGroupSynchronization :: RESULT_PROPERTY_DEPARTMENT_ID) . '
                AND type = 3 AND date_start <= \'' . $this->get_academic_year_end() . '\' AND (date_end >= \'' .
                 $this->get_academic_year_end() . '\' OR date_end is null)';
            $users = $this->get_result($query);

            while ($user = $users->next_result(false))
            {
                $user_mails[] = $user['person_id'];
            }
        }

        return $user_mails;
    }
}
