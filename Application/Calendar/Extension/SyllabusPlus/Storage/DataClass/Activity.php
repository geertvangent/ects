<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\DataClass;

use Chamilo\Libraries\Storage\DataClass\DataClass;

/**
 *
 * @package Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\DataClass
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
abstract class Activity extends DataClass
{
    // Properties
    const PROPERTY_YEAR = 'year';
    const PROPERTY_MODULE_ID = 'module_id';
    const PROPERTY_NAME = 'name';
    const PROPERTY_START_TIME = 'start_time';
    const PROPERTY_END_TIME = 'end_time';
    const PROPERTY_LOCATION = 'location';
    const PROPERTY_TYPE_CODE = 'type_code';
    const PROPERTY_TYPE = 'type';
    const PROPERTY_STUDENT_GROUP = 'student_group';
    const PROPERTY_TEACHER = 'teacher';

    /**
     *
     * @param string[] $extended_property_names
     * @return string[]
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self::PROPERTY_YEAR;
        $extended_property_names[] = self::PROPERTY_MODULE_ID;
        $extended_property_names[] = self::PROPERTY_NAME;
        $extended_property_names[] = self::PROPERTY_START_TIME;
        $extended_property_names[] = self::PROPERTY_END_TIME;
        $extended_property_names[] = self::PROPERTY_LOCATION;
        $extended_property_names[] = self::PROPERTY_TYPE_CODE;
        $extended_property_names[] = self::PROPERTY_TYPE;
        $extended_property_names[] = self::PROPERTY_STUDENT_GROUP;
        $extended_property_names[] = self::PROPERTY_TEACHER;

        return parent::get_default_property_names($extended_property_names);
    }

    /**
     *
     * @return string
     */
    public function getYear()
    {
        return $this->get_default_property(self::PROPERTY_YEAR);
    }

    /**
     *
     * @param string $year
     */
    public function setYear($year)
    {
        $this->set_default_property(self::PROPERTY_YEAR, $year);
    }

    /**
     *
     * @return string
     */
    public function getModuleId()
    {
        return $this->get_default_property(self::PROPERTY_MODULE_ID);
    }

    /**
     *
     * @param string $module_id
     */
    public function setModuleId($module_id)
    {
        $this->set_default_property(self::PROPERTY_MODULE_ID, $module_id);
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return $this->get_default_property(self::PROPERTY_NAME);
    }

    /**
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->set_default_property(self::PROPERTY_NAME, $name);
    }

    /**
     *
     * @return string
     */
    public function getStartTime()
    {
        return $this->get_default_property(self::PROPERTY_START_TIME);
    }

    /**
     *
     * @param string $start_time
     */
    public function setStartTime($start_time)
    {
        $this->set_default_property(self::PROPERTY_START_TIME, $start_time);
    }

    /**
     *
     * @return string
     */
    public function getEndTime()
    {
        return $this->get_default_property(self::PROPERTY_END_TIME);
    }

    /**
     *
     * @param string $end_time
     */
    public function setEndTime($end_time)
    {
        $this->set_default_property(self::PROPERTY_END_TIME, $end_time);
    }

    /**
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->get_default_property(self::PROPERTY_LOCATION);
    }

    /**
     *
     * @param string $location
     */
    public function setLocation($location)
    {
        $this->set_default_property(self::PROPERTY_LOCATION, $location);
    }

    /**
     *
     * @return string
     */
    public function getTypeCode()
    {
        return $this->get_default_property(self::PROPERTY_TYPE_CODE);
    }

    /**
     *
     * @param string $type_code
     */
    public function setTypeCode($type_code)
    {
        $this->set_default_property(self::PROPERTY_TYPE_CODE, $type_code);
    }

    /**
     *
     * @return string
     */
    public function getType()
    {
        return $this->get_default_property(self::PROPERTY_TYPE);
    }

    /**
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->set_default_property(self::PROPERTY_TYPE, $type);
    }

    /**
     *
     * @return string
     */
    public function getStudentGroup()
    {
        return $this->get_default_property(self::PROPERTY_STUDENT_GROUP);
    }

    /**
     *
     * @param string $student_group
     */
    public function setStudentGroup($student_group)
    {
        $this->set_default_property(self::PROPERTY_STUDENT_GROUP, $student_group);
    }

    /**
     *
     * @return string
     */
    public function getTeacher()
    {
        return $this->get_default_property(self::PROPERTY_TEACHER);
    }

    /**
     *
     * @param string $teacher
     */
    public function setTeacher($teacher)
    {
        $this->set_default_property(self::PROPERTY_TEACHER, $teacher);
    }
}