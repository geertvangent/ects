<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\DataClass;

use Chamilo\Libraries\Storage\DataClass\DataClass;

/**
 *
 * @package Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\DataClass
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
abstract class StudentGroup extends DataClass
{
    // Properties
    const PROPERTY_YEAR = 'year';
    const PROPERTY_PERSON_ID = 'person_id';
    const PROPERTY_GROUP_ID = 'group_id';
    const PROPERTY_GROUP_CODE = 'group_code';
    const PROPERTY_GROUP_NAME = 'group_name';
    const PROPERTY_FACULTY_ID = 'faculty_id';
    const PROPERTY_FACULTY_CODE = 'faculty_code';
    const PROPERTY_FACULTY_NAME = 'faculty_name';

    /**
     *
     * @param string[] $extended_property_names
     * @return string[]
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self::PROPERTY_YEAR;
        $extended_property_names[] = self::PROPERTY_PERSON_ID;
        $extended_property_names[] = self::PROPERTY_GROUP_ID;
        $extended_property_names[] = self::PROPERTY_GROUP_CODE;
        $extended_property_names[] = self::PROPERTY_GROUP_NAME;
        $extended_property_names[] = self::PROPERTY_FACULTY_ID;
        $extended_property_names[] = self::PROPERTY_FACULTY_CODE;
        $extended_property_names[] = self::PROPERTY_FACULTY_NAME;

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
     * @return integer
     */
    public function getPersonId()
    {
        return $this->get_default_property(self::PROPERTY_PERSON_ID);
    }

    /**
     *
     * @param string $person_id
     */
    public function setPersonId($person_id)
    {
        $this->set_default_property(self::PROPERTY_PERSON_ID, $person_id);
    }

    /**
     *
     * @return string
     */
    public function getGroupId()
    {
        return $this->get_default_property(self::PROPERTY_GROUP_ID);
    }

    /**
     *
     * @param string $group_id
     */
    public function setGroupId($group_id)
    {
        $this->set_default_property(self::PROPERTY_GROUP_ID, $group_id);
    }

    /**
     *
     * @return string
     */
    public function getGroupCode()
    {
        return $this->get_default_property(self::PROPERTY_GROUP_CODE);
    }

    /**
     *
     * @param string $group_code
     */
    public function setGroupCode($group_code)
    {
        $this->set_default_property(self::PROPERTY_GROUP_CODE, $group_code);
    }

    /**
     *
     * @return string
     */
    public function getGroupName()
    {
        return $this->get_default_property(self::PROPERTY_GROUP_NAME);
    }

    /**
     *
     * @param string $group_name
     */
    public function setGroupName($group_name)
    {
        $this->set_default_property(self::PROPERTY_GROUP_NAME, $group_name);
    }

    /**
     *
     * @return string
     */
    public function getFacultyId()
    {
        return $this->get_default_property(self::PROPERTY_FACULTY_ID);
    }

    /**
     *
     * @param string $faculty_id
     */
    public function setFacultyId($faculty_id)
    {
        $this->set_default_property(self::PROPERTY_FACULTY_ID, $faculty_id);
    }

    /**
     *
     * @return string
     */
    public function getFacultyCode()
    {
        return $this->get_default_property(self::PROPERTY_FACULTY_CODE);
    }

    /**
     *
     * @param string $faculty_code
     */
    public function setFacultyCode($faculty_code)
    {
        $this->set_default_property(self::PROPERTY_FACULTY_CODE, $faculty_code);
    }

    /**
     *
     * @return string
     */
    public function getFacultyName()
    {
        return $this->get_default_property(self::PROPERTY_FACULTY_NAME);
    }

    /**
     *
     * @param string $faculty_name
     */
    public function setFacultyName($faculty_name)
    {
        $this->set_default_property(self::PROPERTY_FACULTY_NAME, $faculty_name);
    }
}