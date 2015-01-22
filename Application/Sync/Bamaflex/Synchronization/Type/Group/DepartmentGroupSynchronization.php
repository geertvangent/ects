<?php
namespace Ehb\Application\Sync\Bamaflex\Synchronization\Type\Group;

use Ehb\Application\Sync\Bamaflex\Synchronization\Type\GroupSynchronization;
/**
 *
 * @package ehb.sync;
 */
class DepartmentGroupSynchronization extends GroupSynchronization
{
    CONST IDENTIFIER = 'DEP';
    const RESULT_PROPERTY_ACADEMIC_YEAR = 'year';
    const RESULT_PROPERTY_DEPARTMENT = 'name';
    const RESULT_PROPERTY_DEPARTMENT_ID = 'id';

    public function get_code()
    {
        return self :: IDENTIFIER . '_' . $this->get_parameter(self :: RESULT_PROPERTY_DEPARTMENT_ID);
    }

    public function get_name()
    {
        return $this->get_parameter(self :: RESULT_PROPERTY_DEPARTMENT);
    }

    public function get_children()
    {
        $children = array();
        $children[] = GroupSynchronization :: factory('user_type_employee', $this);
        $children[] = GroupSynchronization :: factory('user_type_teacher', $this);
        $children[] = GroupSynchronization :: factory('user_type_guest_teacher', $this);
        $children[] = GroupSynchronization :: factory('user_type_student', $this);
        return $children;
    }
}
