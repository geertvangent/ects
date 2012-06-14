<?php
namespace application\ehb_sync\bamaflex;

/**
 * @package ehb.sync;
 */

use group\GroupDataManager;
use group\Group;

use common\libraries\AndCondition;
use common\libraries\EqualityCondition;

class UserTypeTeacherGroupSynchronization extends GroupSynchronization
{
    CONST IDENTIFIER = 'OP';

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
        return 'Onderwijzend Personeel';
    }

    function get_children()
    {
        $query = 'EXEC [dbo].[sp_structure_trainings] @academiejaar = N\'' . $this->get_academic_year() . '\', @departement_id = ' . $this->get_synchronization()->get_parameter(DepartmentGroupSynchronization :: RESULT_PROPERTY_DEPARTMENT_ID);
        $trainings = $this->get_result($query);

        $children = array();
        while ($training = $trainings->next_result(false))
        {
            $children[] = GroupSynchronization :: factory('teacher_training', $this, $training);
        }
        return $children;
    }
}
?>