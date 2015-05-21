<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\User\Component;

use Ehb\Application\Avilarts\Course\Storage\DataClass\CourseGroupRelation;

class StatusChangerUserTeacherComponent extends StatusChangerComponent
{

    public function get_relation()
    {
        return \Chamilo\Application\Weblcms\Course\Storage\DataManager :: retrieve_course_user_relation_by_course_and_user(
            $this->get_course_id(), 
            $this->object);
    }

    public function get_status()
    {
        return CourseGroupRelation :: STATUS_TEACHER;
    }
}
