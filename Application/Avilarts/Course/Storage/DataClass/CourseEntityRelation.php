<?php
namespace Ehb\Application\Avilarts\Course\Storage\DataClass;

use Chamilo\Libraries\Storage\DataClass\DataClass;

/**
 * This class represents a relation between a weblcms course and an entity (a
 * user, a group...)
 * 
 * @package application\Avilarts\course;
 * @author Sven Vanpoucke - Hogeschool Gent
 */
abstract class CourseEntityRelation extends DataClass
{
    /**
     * **************************************************************************************************************
     * Table Properties *
     * **************************************************************************************************************
     */
    const PROPERTY_COURSE_ID = 'course_id';
    const PROPERTY_STATUS = 'status';
    
    /**
     * **************************************************************************************************************
     * Status Definitions *
     * **************************************************************************************************************
     */
    const STATUS_TEACHER = 1;
    const STATUS_STUDENT = 5;
    
    /**
     * **************************************************************************************************************
     * Foreign properties *
     * **************************************************************************************************************
     */
    const FOREIGN_PROPERTY_COURSE = 'course';

    /**
     * **************************************************************************************************************
     * Inherited Functionality *
     * **************************************************************************************************************
     */
    
    /**
     * Returns the default properties of this dataclass
     * 
     * @return String[] - The property names.
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_COURSE_ID;
        $extended_property_names[] = self :: PROPERTY_STATUS;
        
        return parent :: get_default_property_names($extended_property_names);
    }

    /**
     * **************************************************************************************************************
     * Getters and Setters *
     * **************************************************************************************************************
     */
    
    /**
     * Returns the course id of this course user relation object
     * 
     * @return int
     */
    public function get_course_id()
    {
        return $this->get_default_property(self :: PROPERTY_COURSE_ID);
    }

    /**
     * Sets the course id of this course user relation object
     * 
     * @param $course_id int
     */
    public function set_course_id($course_id)
    {
        $this->set_default_property(self :: PROPERTY_COURSE_ID, $course_id);
    }

    /**
     * Returns the status of this course user relation object
     * 
     * @return int
     */
    public function get_status()
    {
        return $this->get_default_property(self :: PROPERTY_STATUS);
    }

    /**
     * Sets the status of this course user relation object
     * 
     * @param $status int
     */
    public function set_status($status)
    {
        $this->set_default_property(self :: PROPERTY_STATUS, $status);
    }

    /**
     * **************************************************************************************************************
     * Foreign Properties Setters / Getters *
     * **************************************************************************************************************
     */
    
    /**
     * Returns the course of this course user relation object
     * 
     * @return \application\Avilarts\course\Course
     */
    public function get_course()
    {
        return $this->get_foreign_property(self :: FOREIGN_PROPERTY_COURSE);
    }

    /**
     * Sets the course of this course user relation object
     * 
     * @param $course \application\Avilarts\course\Course
     */
    public function set_course(\Ehb\Application\Avilarts\Course\Storage\DataClass\Course $course)
    {
        $this->set_foreign_property(self :: FOREIGN_PROPERTY_COURSE, $course);
    }
}
