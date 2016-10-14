<?php
namespace Ehb\Application\Sync\Bamaflex\Synchronization\Type\Group;

use Ehb\Application\Sync\Bamaflex\Synchronization\Type\GroupSynchronization;

/**
 *
 * @package Ehb\Application\Sync\Bamaflex\Synchronization\Type\Group
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
class CurrentFacultyEmployeeTeacherGroupSynchronization extends GroupSynchronization
{
    CONST IDENTIFIER = 'OP';

    public function get_code()
    {
        return $this->get_synchronization()->get_code() . '_' . self::IDENTIFIER;
    }

    public function get_name()
    {
        return 'Docenten';
    }

    public function get_user_official_codes()
    {
        $user_mails = array();

        $facultySynchronization = $this->get_synchronization()->get_synchronization();
        $facultyCode = CurrentGroupSynchronization::getEmployeeFacultyCode(
            $facultySynchronization->get_parameter(CurrentFacultyGroupSynchronization::RESULT_PROPERTY_CODE));

        if ($facultyCode)
        {
            $facultyCodeString = implode('\', \'', $facultyCode);

            $query = 'SELECT DISTINCT person_id FROM [dbo].[v_sync_current_employee] WHERE employee_type = 2 AND faculty_code IN (\'' .
                 $facultyCodeString . '\') AND (date_end >= \'' . date('Y-m-d', strtotime('-2 months')) .
                 '\' OR date_end IS NULL)';

            $users = $this->get_result($query);

            while ($user = $users->next_result(false))
            {
                $user_mails[] = $user['person_id'];
            }
        }

        return $user_mails;
    }
}
