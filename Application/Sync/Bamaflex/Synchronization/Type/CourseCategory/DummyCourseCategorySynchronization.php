<?php
namespace Chamilo\Application\EhbSync\Bamaflex\Synchronization\Type\CourseCategory;

use Chamilo\Application\Weblcms\Storage\DataClass\CourseCategory;
use Chamilo\Application\EhbSync\Bamaflex\Synchronization\Type\CourseCategorySynchronization;

class DummyCourseCategorySynchronization extends CourseCategorySynchronization
{

    private $year;

    public function __construct(CourseCategory $group, $year)
    {
        $this->set_current_group($group);
        $this->year = $year;
    }

    /**
     *
     * @return string
     */
    public function get_year()
    {
        return $this->year;
    }

    /**
     *
     * @param string $year
     */
    public function set_year($year)
    {
        $this->year = $year;
    }
}
