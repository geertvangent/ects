<?php
namespace Ehb\Application\Discovery\Module\Elo\Implementation\Chamilo\Type;

use Ehb\Application\Discovery\Module\Elo\Implementation\Chamilo\TypeData;

class CourseListData extends TypeData
{
    const PROPERTY_YEAR = 'year';
    const PROPERTY_COURSE = 'course';

    public function get_year()
    {
        return $this->get_default_property(self :: PROPERTY_YEAR);
    }

    public function set_year($year)
    {
        $this->set_default_property(self :: PROPERTY_YEAR, $year);
    }

    public function get_course()
    {
        return $this->get_default_property(self :: PROPERTY_COURSE);
    }

    public function set_course($course)
    {
        $this->set_default_property(self :: PROPERTY_COURSE, $course);
    }

    public static function get_filters($filters = array())
    {
        $filters[] = self :: PROPERTY_YEAR;

        return parent :: get_filters($filters);
    }

    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_COURSE;
        $extended_property_names[] = self :: PROPERTY_YEAR;

        return parent :: get_default_property_names($extended_property_names);
    }
}
