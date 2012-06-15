<?php
namespace application\ehb_sync\bamaflex;

/**
 * @package ehb.sync;
 */

use group\GroupDataManager;
use group\Group;

use common\libraries\AndCondition;
use common\libraries\EqualityCondition;

class DepartmentGroupSynchronization extends GroupSynchronization
{
    CONST IDENTIFIER = 'DEP';

    const RESULT_PROPERTY_ACADEMIC_YEAR = 'year';
    const RESULT_PROPERTY_DEPARTMENT = 'name';
    const RESULT_PROPERTY_DEPARTMENT_ID = 'id';

    function get_code()
    {
        return self :: IDENTIFIER . '_' . $this->get_parameter(self :: RESULT_PROPERTY_DEPARTMENT_ID);
    }

    function get_name()
    {
        return $this->get_parameter(self :: RESULT_PROPERTY_DEPARTMENT);
    }

    function get_children()
    {
        $children = array();
        $children[] = GroupSynchronization :: factory('user_type_employee', $this);
        $children[] = GroupSynchronization :: factory('user_type_teacher', $this);
        $children[] = GroupSynchronization :: factory('user_type_student', $this);
        return $children;
    }
}
?>