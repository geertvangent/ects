<?php
namespace application\ehb_sync\bamaflex;

use application\weblcms\CourseCategory;

class DummyCourseCategorySynchronization extends CourseCategorySynchronization
{

    public function __construct(CourseCategory $group)
    {
        $this->set_current_group($group);
    }
}
