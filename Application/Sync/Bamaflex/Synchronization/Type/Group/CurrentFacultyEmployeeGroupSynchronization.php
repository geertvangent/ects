<?php
namespace Ehb\Application\Sync\Bamaflex\Synchronization\Type\Group;

use Ehb\Application\Sync\Bamaflex\Synchronization\Type\GroupSynchronization;

/**
 *
 * @package Ehb\Application\Sync\Bamaflex\Synchronization\Type\Group
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
class CurrentFacultyEmployeeGroupSynchronization extends GroupSynchronization
{
    CONST IDENTIFIER = 'EMP';

    public function get_code()
    {
        return $this->get_synchronization()->get_code() . '_' . self::IDENTIFIER;
    }

    public function get_name()
    {
        return 'Personeel';
    }

    public function get_children()
    {
        $children = array();

        $children[] = GroupSynchronization::factory('current_faculty_employee_admin', $this);
        $children[] = GroupSynchronization::factory('current_faculty_employee_teacher', $this);
        $children[] = GroupSynchronization::factory('current_faculty_employee_guest_teacher', $this);

        return $children;
    }
}
