<?php
namespace Chamilo\Application\EhbSync\Bamaflex\Synchronization\Type\Group;

/**
 *
 * @package ehb.sync;
 */
class StudentCourseGroupSynchronization extends CourseGroupSynchronization
{
    CONST IDENTIFIER = 'COU_STU';

    public function get_group_type()
    {
        return 'student_course';
    }

    public function get_department_id()
    {
        return $this->get_synchronization()->get_training()->get_department_id();
    }

    public function get_training_id()
    {
        return $this->get_synchronization()->get_training()->get_parameter(
            StudentTrainingGroupSynchronization :: RESULT_PROPERTY_TRAINING_ID);
    }

    public function get_user_official_codes()
    {
        $user_mails = array();
        if ($this->get_parameter(self :: RESULT_PROPERTY_TYPE) != 2)
        {
            $query = 'SELECT DISTINCT person_id FROM [dbo].[v_discovery_list_user_student_basic]  WHERE programme_id = ' .
                 $this->get_parameter(self :: RESULT_PROPERTY_COURSE_ID) .
                 ' AND type = 1 AND result != 8 AND (programme_type = 1 OR programme_type = 6)';
            $users = $this->get_result($query);

            while ($user = $users->next_result(false))
            {
                $user_mails[] = $user['person_id'];
            }
        }
        return $user_mails;
    }
}
