<?php
namespace Ehb\Application\Sync\Bamaflex\Synchronization\Type\Group\Current\Student;

use Ehb\Application\Sync\Bamaflex\Synchronization\Type\GroupSynchronization;

/**
 *
 * @package Ehb\Application\Sync\Bamaflex\Synchronization\Type\Group
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
class Training extends GroupSynchronization
{
    CONST IDENTIFIER = 'TRA';

    public function get_code()
    {
        return $this->get_synchronization()->get_code() . '_' . self::IDENTIFIER;
    }

    public function get_name()
    {
        return 'Opleidingen';
    }

    // public function get_children()
    // {
    // $children = array();

    // $children[] = GroupSynchronization::factory('current_faculty_employee', $this);
    // $children[] = GroupSynchronization::factory('current_faculty_student', $this);

    // return $children;
    // }
}
