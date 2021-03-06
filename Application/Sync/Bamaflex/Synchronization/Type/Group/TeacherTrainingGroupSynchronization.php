<?php
namespace Ehb\Application\Sync\Bamaflex\Synchronization\Type\Group;

use Ehb\Application\Sync\Bamaflex\Synchronization\Type\GroupSynchronization;

/**
 *
 * @package ehb.sync;
 */
class TeacherTrainingGroupSynchronization extends TrainingGroupSynchronization
{

    /*
     * (non-PHPdoc) @see application\ehb_sync\bamaflex.TrainingGroupSynchronization::get_group_type()
     */
    public function get_group_type()
    {
        return UserTypeTeacherGroupSynchronization::IDENTIFIER;
    }

    public function get_children()
    {
        $query = 'SELECT * FROM [INFORDATSYNC].[dbo].[v_discovery_course_basic] WHERE training_id = ' .
             $this->get_parameter(self::RESULT_PROPERTY_TRAINING_ID) . ' AND parent_id IS NULL AND exchange = 0';
        $courses = $this->get_result($query);
        
        $children = array();
        while ($course = $courses->next_result(false))
        {
            $children[] = GroupSynchronization::factory('teacher_course', $this, $course);
        }
        return $children;
    }
}
