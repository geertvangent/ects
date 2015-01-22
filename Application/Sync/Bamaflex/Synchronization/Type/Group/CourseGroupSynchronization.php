<?php
namespace Ehb\Application\Sync\Bamaflex\Synchronization\Type\Group;

use Ehb\Application\Sync\Bamaflex\Synchronization\Type\GroupSynchronization;
/**
 *
 * @package ehb.sync;
 */
abstract class CourseGroupSynchronization extends GroupSynchronization
{
    CONST IDENTIFIER = 'COU';
    const RESULT_PROPERTY_COURSE = 'name';
    const RESULT_PROPERTY_COURSE_ID = 'id';
    const RESULT_PROPERTY_PARENT_ID = 'parent_id';
    const RESULT_PROPERTY_TYPE = 'programme_type';

    public function get_code()
    {
        return $this :: IDENTIFIER . '_' . $this->get_parameter(self :: RESULT_PROPERTY_COURSE_ID);
    }

    public function get_name()
    {
        return $this->get_parameter(self :: RESULT_PROPERTY_COURSE);
    }

    abstract public function get_department_id();

    abstract public function get_training_id();

    abstract public function get_group_type();

    public function get_children()
    {
        $children = array();

        if (is_null($this->get_parameter(self :: RESULT_PROPERTY_PARENT_ID)) &&
             $this->get_parameter(self :: RESULT_PROPERTY_TYPE) == 2)
        {
            $query = 'SELECT * FROM [INFORDATSYNC].[dbo].[v_discovery_course_basic] WHERE parent_id = ' .
             $this->get_parameter(self :: RESULT_PROPERTY_COURSE_ID) . ' AND exchange = 0';

        $courses = $this->get_result($query);

        while ($course = $courses->next_result(false))
        {
            $children[] = GroupSynchronization :: factory($this->get_group_type(), $this, $course);
        }
    }

    return $children;
}
}
