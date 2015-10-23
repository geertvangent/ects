<?php
namespace Ehb\Application\Discovery\Module\Elo\Implementation\Chamilo\Type;

use Ehb\Application\Discovery\Module\Elo\Implementation\Chamilo\TypeData;

class CourseAccessData extends TypeData
{
    const PROPERTY_COURSE = 'course';
    const PROPERTY_TOOL = 'tool';
    const PROPERTY_USER_ID = 'user_id';
    const PROPERTY_USER_OFFICIAL_CODE = 'user_official_code';
    const PROPERTY_USER_EMAIL = 'user_email';

    public static function get_filters($filters = array())
    {
        $filters[] = self :: PROPERTY_COURSE;
        $filters[] = self :: PROPERTY_TOOL;

        return parent :: get_filters($filters);
    }

    public function get_course()
    {
        return $this->get_default_property(self :: PROPERTY_COURSE);
    }

    public function set_course($course)
    {
        $this->set_default_property(self :: PROPERTY_COURSE, $course);
    }

    public function get_tool()
    {
        return $this->get_default_property(self :: PROPERTY_TOOL);
    }

    public function set_tool($tool)
    {
        $this->set_default_property(self :: PROPERTY_TOOL, $tool);
    }

    public function get_user_id()
    {
        return $this->get_default_property(self :: PROPERTY_USER_ID);
    }

    public function set_user_id($user_id)
    {
        $this->set_default_property(self :: PROPERTY_USER_ID, $user_id);
    }

    public function get_user_official_code()
    {
        return $this->get_default_property(self :: PROPERTY_USER_OFFICIAL_CODE);
    }

    public function set_user_official_code($user_official_code)
    {
        $this->set_default_property(self :: PROPERTY_USER_OFFICIAL_CODE, $user_official_code);
    }

    public function get_user_email()
    {
        return $this->get_default_property(self :: PROPERTY_USER_EMAIL);
    }

    public function set_user_email($user_email)
    {
        $this->set_default_property(self :: PROPERTY_USER_EMAIL, $user_email);
    }

    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_COURSE;
        $extended_property_names[] = self :: PROPERTY_TOOL;
        $extended_property_names[] = self :: PROPERTY_USER_ID;
        $extended_property_names[] = self :: PROPERTY_USER_OFFICIAL_CODE;
        $extended_property_names[] = self :: PROPERTY_USER_EMAIL;

        return parent :: get_default_property_names($extended_property_names);
    }
}
