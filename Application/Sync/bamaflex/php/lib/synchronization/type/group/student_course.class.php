<?php
namespace application\ehb_sync\bamaflex;

/**
 *
 * @package ehb.sync;
 */

use common\libraries\WebApplication;

use group\Group;

use application\weblcms\CourseGroupRelation;

use group\GroupDataManager;

use common\libraries\EqualityCondition;

use application\weblcms\Course;
use application\weblcms\WeblcmsDataManager;

use common\libraries\Utilities;

require_once dirname(__FILE__) . '/course.class.php';

class StudentCourseGroupSynchronization extends CourseGroupSynchronization
{
    CONST IDENTIFIER = 'COU_STU';

    function get_group_type()
    {
        return 'student_course';
    }

    function get_department_id()
    {
        return $this->get_synchronization()->get_training()->get_department_id();
    }

    function get_training_id()
    {
        return $this->get_synchronization()->get_training()->get_parameter(StudentTrainingGroupSynchronization :: RESULT_PROPERTY_TRAINING_ID);
    }

    function get_user_official_codes()
    {
        $user_mails = array();
        if ($this->get_parameter(self :: RESULT_PROPERTY_HAS_CHILDREN) == 0)
        {
            $query = 'SELECT DISTINCT person_id FROM [dbo].[v_discovery_list_user]  WHERE programme_id = "' . $this->get_parameter(self :: RESULT_PROPERTY_COURSE_ID) . '" AND type = 1 AND result != 8 AND programme_type = 1';
            $users = $this->get_result($query);
            
            while ($user = $users->next_result(false))
            {
                $user_mails[] = $user['person_id'];
            }
        }
        return $user_mails;
    }

    function run()
    {
        parent :: run();
        
        if (WebApplication :: is_active('weblcms'))
            if ($this->get_parameter(self :: RESULT_PROPERTY_HAS_CHILDREN) == 0)
            {
                $course_code = 'COURSE_' . $this->get_parameter(self :: RESULT_PROPERTY_COURSE_ID);
                $condition = new EqualityCondition(Course :: PROPERTY_VISUAL, $course_code);
                
                $course_exists = WeblcmsDataManager :: get_instance()->count_courses($condition);
                
                if (! $course_exists)
                {
                    // Create the course
                    $course = new Course();
                    $course->set_course_type_id(2);
                    $course->set_name($this->convert_to_utf8($this->get_parameter(self :: RESULT_PROPERTY_COURSE)));
                    $course->set_titular(2);
                    $course->set_category(1);
                    $course->set_visual($this->get_parameter(self :: RESULT_PROPERTY_COURSE_ID));
                    
                    $course->set_language('en');
                    $course->set_visibility(1);
                    $course->set_access(1);
                    $course->set_max_number_of_members(0);
                    
                    $course->set_intro_text(1);
                    $course->set_student_view(1);
                    $course->set_layout(1);
                    $course->set_tool_shortcut(1);
                    $course->set_menu(1);
                    $course->set_feedback(1);
                    $course->set_course_code_visible(1);
                    $course->set_course_manager_name_visible(1);
                    
                    $course->set_direct_subscribe_available(1);
                    $succes = $course->create();
                    
                    // Subscribe the student user group
                    $wdm = WeblcmsDataManager :: get_instance();
                    $succes &= $wdm->subscribe_group_to_course($course, $this->get_current_group()->get_id(), CourseGroupRelation :: STATUS_STUDENT);
                    
                    // Subscripe the teacher user group
                    $teacher_group_code = TeacherCourseGroupSynchronization :: IDENTIFIER . '_' . $this->get_parameter(self :: RESULT_PROPERTY_COURSE_ID);
                    
                    $condition = new EqualityCondition(Group :: PROPERTY_CODE, $teacher_group_code);
                    $groups = GroupDataManager :: get_instance()->retrieve_groups($condition);
                    
                    if ($groups->size() > 0)
                    {
                        $group = $groups->next_result(false);
                        $succes &= $wdm->subscribe_group_to_course($course, $group['id'], CourseGroupRelation :: STATUS_TEACHER);
                    }
                }
            }
    }
}
?>