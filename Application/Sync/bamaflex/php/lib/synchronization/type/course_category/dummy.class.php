<?php
namespace application\ehb_sync\bamaflex;

use application\weblcms\CourseCategory;

class DummyCourseCategorySynchronization extends CourseCategorySynchronization
{

    function __construct(CourseCategory $group)
    {
        $this->set_current_group($group);
    }
}
