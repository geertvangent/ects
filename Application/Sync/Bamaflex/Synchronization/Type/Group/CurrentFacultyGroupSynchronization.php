<?php
namespace Ehb\Application\Sync\Bamaflex\Synchronization\Type\Group;

use Ehb\Application\Sync\Bamaflex\Synchronization\Type\GroupSynchronization;

/**
 *
 * @package ehb.sync;
 */
class CurrentFacultyGroupSynchronization extends GroupSynchronization
{
    CONST IDENTIFIER = 'CUR_FAC';
    const RESULT_PROPERTY_CODE = 'code';
    const RESULT_PROPERTY_DEPARTMENT = 'name';

    public function get_code()
    {
        return self::IDENTIFIER . '_' . $this->get_parameter(self::RESULT_PROPERTY_CODE);
    }

    public function get_name()
    {
        return $this->get_parameter(self::RESULT_PROPERTY_DEPARTMENT);
    }

    public function get_children()
    {
        $children = array();

        $children[] = GroupSynchronization::factory('current_faculty_employee', $this);
        $children[] = GroupSynchronization::factory('current_faculty_student', $this);

        return $children;
    }
}
