<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\DataClass;

use Chamilo\Libraries\Storage\DataClass\DataClass;

/**
 *
 * @package Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\DataClass
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
class ScheduledGroup extends DataClass
{
    // Properties
    const PROPERTY_FACULTY_ID = 'faculty_id';
    const PROPERTY_FACULTY_NAME = 'faculty_name';
    const PROPERTY_TRAINING_ID = 'training_id';
    const PROPERTY_TRAINING_NAME = 'training_name';
    const PROPERTY_ENROLLMENT_ID = 'enrollment_id';
    const PROPERTY_PERSON_ID = 'person_id';
    const PROPERTY_LAST_NAME = 'last_name';
    const PROPERTY_FIRST_NAME = 'first_name';
    const PROPERTY_COURSE_ENROLLMENT_ID = 'course_enrollment_id';
    const PROPERTY_COURSE = 'course';
    const PROPERTY_GROUP_ID = 'group_id';
    const PROPERTY_GROUP_TYPE = 'group_type';
    const PROPERTY_SCHEDULED = 'scheduled';
    const PROPERTY_YEAR = 'year';
    const PROPERTY_STRUCK = 'struck';

    // Pseudo-properties used by the Repository
    const PROPERTY_COUNT_SCHEDULED = 'count_scheduled';
    const PROPERTY_COUNT_TO_BE_SCHEDULED = 'count_to_be_scheduled';

    /**
     *
     * @param string[] $extended_property_names
     * @return string[]
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self::PROPERTY_FACULTY_ID;
        $extended_property_names[] = self::PROPERTY_FACULTY_NAME;
        $extended_property_names[] = self::PROPERTY_TRAINING_ID;
        $extended_property_names[] = self::PROPERTY_TRAINING_NAME;
        $extended_property_names[] = self::PROPERTY_ENROLLMENT_ID;
        $extended_property_names[] = self::PROPERTY_PERSON_ID;
        $extended_property_names[] = self::PROPERTY_LAST_NAME;
        $extended_property_names[] = self::PROPERTY_FIRST_NAME;
        $extended_property_names[] = self::PROPERTY_COURSE_ENROLLMENT_ID;
        $extended_property_names[] = self::PROPERTY_COURSE;
        $extended_property_names[] = self::PROPERTY_GROUP_ID;
        $extended_property_names[] = self::PROPERTY_GROUP_TYPE;
        $extended_property_names[] = self::PROPERTY_SCHEDULED;
        $extended_property_names[] = self::PROPERTY_YEAR;
        $extended_property_names[] = self::PROPERTY_STRUCK;

        return $extended_property_names;
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

    /**
     *
     * @return string
     */
    public function getTrainingId()
    {
        return $this->get_default_property(self::PROPERTY_TRAINING_ID);
    }

    /**
     *
     * @param string $training_id
     */
    public function setTrainingId($training_id)
    {
        $this->set_default_property(self::PROPERTY_TRAINING_ID, $training_id);
    }

    /**
     *
     * @return string
     */
    public function getTrainingName()
    {
        return $this->get_default_property(self::PROPERTY_TRAINING_NAME);
    }

    /**
     *
     * @param string $training_name
     */
    public function setTrainingName($training_name)
    {
        $this->set_default_property(self::PROPERTY_TRAINING_NAME, $training_name);
    }

    /**
     *
     * @return string
     */
    public function getEnrollmentId()
    {
        return $this->get_default_property(self::PROPERTY_ENROLLMENT_ID);
    }

    /**
     *
     * @param string $enrollment_id
     */
    public function setEnrollmentId($enrollment_id)
    {
        $this->set_default_property(self::PROPERTY_ENROLLMENT_ID, $enrollment_id);
    }

    /**
     *
     * @return string
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
    public function getLastName()
    {
        return $this->get_default_property(self::PROPERTY_LAST_NAME);
    }

    /**
     *
     * @param string $last_name
     */
    public function setLastName($last_name)
    {
        $this->set_default_property(self::PROPERTY_LAST_NAME, $last_name);
    }

    /**
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->get_default_property(self::PROPERTY_FIRST_NAME);
    }

    /**
     *
     * @param string $first_name
     */
    public function setFirstName($first_name)
    {
        $this->set_default_property(self::PROPERTY_FIRST_NAME, $first_name);
    }

    /**
     *
     * @return string
     */
    public function getCourseEnrollmentId()
    {
        return $this->get_default_property(self::PROPERTY_COURSE_ENROLLMENT_ID);
    }

    /**
     *
     * @param string $course_enrollment_id
     */
    public function setCourseEnrollmentId($course_enrollment_id)
    {
        $this->set_default_property(self::PROPERTY_COURSE_ENROLLMENT_ID, $course_enrollment_id);
    }

    /**
     *
     * @return string
     */
    public function getCourse()
    {
        return $this->get_default_property(self::PROPERTY_COURSE);
    }

    /**
     *
     * @param string $course
     */
    public function setCourse($course)
    {
        $this->set_default_property(self::PROPERTY_COURSE, $course);
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
    public function getGroupType()
    {
        return $this->get_default_property(self::PROPERTY_GROUP_TYPE);
    }

    /**
     *
     * @param string $group_type
     */
    public function setGroupType($group_type)
    {
        $this->set_default_property(self::PROPERTY_GROUP_TYPE, $group_type);
    }

    /**
     *
     * @return string
     */
    public function getScheduled()
    {
        return $this->get_default_property(self::PROPERTY_SCHEDULED);
    }

    /**
     *
     * @param string $scheduled
     */
    public function setScheduled($scheduled)
    {
        $this->set_default_property(self::PROPERTY_SCHEDULED, $scheduled);
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
    public function getStruck()
    {
        return $this->get_default_property(self::PROPERTY_STRUCK);
    }

    /**
     *
     * @param string $struck
     */
    public function setStruck($struck)
    {
        $this->set_default_property(self::PROPERTY_STRUCK, $struck);
    }

    /**
     *
     * @return string
     */
    public static function get_table_name()
    {
        return 'v_syllabus_scheduled_group';
    }
}