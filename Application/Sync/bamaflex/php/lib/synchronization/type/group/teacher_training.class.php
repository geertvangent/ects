<?php
namespace application\ehb_sync\bamaflex;

/**
 * @package ehb.sync;
 */

use common\libraries\Utilities;

require_once dirname(__FILE__) . '/training.class.php';
require_once dirname(__FILE__) . '/user_type_teacher.class.php';

class TeacherTrainingGroupSynchronization extends TrainingGroupSynchronization
{

    /* (non-PHPdoc)
     * @see application\ehb_sync\bamaflex.TrainingGroupSynchronization::get_group_type()
     */
    function get_group_type()
    {
        return UserTypeTeacherGroupSynchronization :: IDENTIFIER;
    }

    function get_children()
    {
        $query = 'EXEC [dbo].[sp_structure_courses_parents] @academiejaar = N\'' . $this->get_academic_year() . '\', @departement_id = ' . $this->get_department_id() . ', @opleiding_id = ' . $this->get_parameter(self :: RESULT_PROPERTY_TRAINING_ID);
        $courses = $this->get_result($query);

        $children = array();
        while ($course = $courses->next_result(false))
        {
            $children[] = GroupSynchronization :: factory('teacher_course', $this, $course);
        }
        return $children;
    }
}
?>