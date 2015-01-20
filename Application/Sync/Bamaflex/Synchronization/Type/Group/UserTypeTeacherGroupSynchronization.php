<?php
namespace Ehb\Application\Sync\Bamaflex\Synchronization\Type\Group;

use Ehb\Application\Sync\Bamaflex\Synchronization\Type\GroupSynchronization;
/**
 *
 * @package ehb.sync;
 */
class UserTypeTeacherGroupSynchronization extends GroupSynchronization
{
    CONST IDENTIFIER = 'OP';

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
        return 'Onderwijzend Personeel';
    }

    public function get_children()
    {
        $query = 'SELECT * FROM [INFORDATSYNC].[dbo].[v_discovery_training_basic] WHERE faculty_id = ' . $this->get_synchronization()->get_parameter(
            DepartmentGroupSynchronization :: RESULT_PROPERTY_DEPARTMENT_ID);

        $trainings = $this->get_result($query);

        $children = array();
        while ($training = $trainings->next_result(false))
        {
            $children[] = GroupSynchronization :: factory('teacher_training', $this, $training);
        }
        return $children;
    }

    public function get_user_official_codes()
    {
        $user_mails = array();

        $query = 'SELECT DISTINCT person_id FROM [dbo].[v_discovery_list_user_teacher_ghost]  WHERE faculty_id = ' .
             $this->get_department()->get_parameter(DepartmentGroupSynchronization :: RESULT_PROPERTY_DEPARTMENT_ID) .
             ' AND type = 3 AND date_start <= \'' . date('Y-m-d', strtotime('+2 months')) . '\' AND (date_end >= \'' .
             date('Y-m-d', strtotime('-2 months')) . '\' OR date_end IS NULL)';
        $users = $this->get_result($query);

        while ($user = $users->next_result(false))
        {
            $user_mails[] = $user['person_id'];
        }

        return $user_mails;
    }
}
