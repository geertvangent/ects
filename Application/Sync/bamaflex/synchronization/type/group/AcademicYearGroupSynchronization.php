<?php
namespace Chamilo\Application\EhbSync\Bamaflex\Synchronization\Type\Group;

/**
 *
 * @package ehb.sync;
 */
class AcademicYearGroupSynchronization extends GroupSynchronization
{
    CONST IDENTIFIER = 'AY';

    public function get_code()
    {
        return self :: IDENTIFIER . '_' . $this->get_synchronization()->get_year();
    }

    public function get_name()
    {
        return $this->get_synchronization()->get_year();
    }

    public function get_children()
    {
        $query = 'SELECT * FROM [INFORDATSYNC].[dbo].[v_discovery_faculty_basic] WHERE year = \'' .
             $this->get_synchronization()->get_year() . '\'';
        $departments = $this->get_result($query);

        $children = array();
        while ($department = $departments->next_result())
        {
            // if ($department['id'] == 85)
            // {
            $children[] = GroupSynchronization :: factory('department', $this, $department);
            // }
        }

        $children[] = GroupSynchronization :: factory('academic_year_extra', $this);
        return $children;
    }
}
