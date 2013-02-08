<?php
namespace application\ehb_sync\bamaflex;

/**
 * @package ehb.sync;
 */



class ArchiveDepartmentGroupSynchronization extends ArchiveGroupSynchronization
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
        $children[] = ArchiveGroupSynchronization :: factory('archive_user_type_employee', $this);
        $children[] = ArchiveGroupSynchronization :: factory('archive_user_type_teacher', $this);
        $children[] = ArchiveGroupSynchronization :: factory('archive_user_type_guest_teacher', $this);
        $children[] = ArchiveGroupSynchronization :: factory('archive_user_type_student', $this);
        return $children;
    }
}
