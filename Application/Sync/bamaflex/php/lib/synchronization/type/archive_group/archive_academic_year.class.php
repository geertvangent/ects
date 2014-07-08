<?php
namespace application\ehb_sync\bamaflex;

/**
 *
 * @package ehb.sync;
 */
class ArchiveAcademicYearGroupSynchronization extends ArchiveGroupSynchronization
{
    CONST IDENTIFIER = 'AY';

    public function get_code()
    {
        return self :: IDENTIFIER . '_' . $this->get_academic_year();
    }

    public function get_name()
    {
        return $this->get_academic_year();
    }

    public function get_children()
    {
        $query = 'SELECT * FROM [INFORDATSYNC].[dbo].[v_discovery_faculty_advanced] WHERE year = \'' .
             $this->get_academic_year() . '\'';
        $departments = $this->get_result($query);

        $children = array();
        while ($department = $departments->next_result())
        {
            $children[] = ArchiveGroupSynchronization :: factory('archive_department', $this, $department);
        }
        return $children;
    }
}
