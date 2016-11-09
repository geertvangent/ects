<?php
namespace Ehb\Application\Sync\Bamaflex\Synchronization\Type\Group;

/**
 *
 * @package ehb.sync;
 */
class TeacherCourseGroupSynchronization extends CourseGroupSynchronization
{
    CONST IDENTIFIER = 'COU_OP';

    public function get_group_type()
    {
        return 'teacher_course';
    }

    public function get_department_id()
    {
        return $this->get_synchronization()->get_department_id();
    }

    public function get_training_id()
    {
        return $this->get_synchronization()->get_parameter(
            TeacherTrainingGroupSynchronization::RESULT_PROPERTY_TRAINING_ID);
    }

    public function get_user_official_codes()
    {
        $user_mails = array();
        if ($this->get_parameter(self::RESULT_PROPERTY_TYPE) != 2)
        {
            $query = 'SELECT DISTINCT person_id FROM [dbo].[v_discovery_list_user_teacher_basic]  WHERE programme_id = ' .
                 $this->get_parameter(self::RESULT_PROPERTY_COURSE_ID) . ' AND type = 2';
            $users = $this->get_result($query);
            
            while ($user = $users->next_result(false))
            {
                $user_mails[] = $user['person_id'];
            }
        }
        return $user_mails;
    }
}
