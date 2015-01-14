<?php
namespace Chamilo\Application\EhbSync\Bamaflex\Synchronization\Type\ArchiveGroup;

use Chamilo\Application\EhbSync\Bamaflex\Synchronization\Type\ArchiveGroupSynchronization;

/**
 *
 * @package ehb.sync;
 */
class ArchiveUserTypeTeacherGroupSynchronization extends ArchiveGroupSynchronization
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
        $faculty_id = $this->get_synchronization()->get_parameter(
            ArchiveDepartmentGroupSynchronization :: RESULT_PROPERTY_DEPARTMENT_ID);
        $faculty_source = $this->get_synchronization()->get_parameter(
            ArchiveDepartmentGroupSynchronization :: RESULT_PROPERTY_DEPARTMENT_SOURCE);

        $query = 'SELECT * FROM [INFORDATSYNC].[dbo].[v_discovery_training_advanced] WHERE faculty_id = ' . $faculty_id .
             ' AND source = ' . $faculty_source;

        $trainings = $this->get_result($query);

        $children = array();
        while ($training = $trainings->next_result(false))
        {
            $children[] = ArchiveGroupSynchronization :: factory('archive_teacher_training', $this, $training);
        }
        return $children;
    }
}
