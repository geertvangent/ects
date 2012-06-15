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

class UserTypeStudentGroupSynchronization extends GroupSynchronization
{
    CONST IDENTIFIER = 'STU';

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
        return 'Studenten';
    }

    function get_children()
    {
        $query = 'SELECT * FROM [INFORDATSYNC].[dbo].[v_discovery_training_basic] WHERE faculty_id = ' . $this->get_synchronization()->get_parameter(DepartmentGroupSynchronization :: RESULT_PROPERTY_DEPARTMENT_ID);
        
        $trainings = $this->get_result($query);
        
        $children = array();
        while ($training = $trainings->next_result(false))
        {
            $children[] = GroupSynchronization :: factory('student_training', $this, $training);
        }
        return $children;
    }
}
?>