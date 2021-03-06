<?php
namespace Ehb\Application\Discovery\Module\Employment\Implementation\Bamaflex;

use Chamilo\Libraries\Utilities\Utilities;
use Ehb\Application\Discovery\DiscoveryItem;

/**
 * application.discovery.module.employment.implementation.bamaflex
 * 
 * @author Magali Gillard
 */
class EmploymentPart extends DiscoveryItem
{
    /**
     *
     * @var integer
     */
    const PROPERTY_ASSIGNMENT_ID = 'assignment_id';
    /**
     *
     * @var string
     */
    const PROPERTY_HOURS = 'hours';
    /**
     *
     * @var string
     */
    const PROPERTY_START_DATE = 'start_date';
    /**
     *
     * @var string
     */
    const PROPERTY_END_DATE = 'end_date';
    /**
     *
     * @var string
     */
    const PROPERTY_ASSIGNMENT_VOLUME = 'assignment_volume';
    /**
     *
     * @var string
     */
    const PROPERTY_VOLUME = 'volume';
    /**
     *
     * @var integer
     */
    const PROPERTY_FACULTY_ID = 'faculty_id';
    /**
     *
     * @var string
     */
    const PROPERTY_FACULTY = 'faculty';
    /**
     *
     * @var integer
     */
    const PROPERTY_TRAINING_ID = 'training_id';
    /**
     *
     * @var string
     */
    const PROPERTY_TRAINING = 'training';
    const PROPERTY_DEPARTMENT = 'department';
    const PROPERTY_DEPARTMENT_ID = 'department_id';

    /**
     * Get the default properties
     * 
     * @param multitype:string $extended_property_names
     * @return multitype:string The property names.
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self::PROPERTY_ASSIGNMENT_ID;
        $extended_property_names[] = self::PROPERTY_HOURS;
        $extended_property_names[] = self::PROPERTY_START_DATE;
        $extended_property_names[] = self::PROPERTY_END_DATE;
        $extended_property_names[] = self::PROPERTY_ASSIGNMENT_VOLUME;
        $extended_property_names[] = self::PROPERTY_VOLUME;
        $extended_property_names[] = self::PROPERTY_FACULTY_ID;
        $extended_property_names[] = self::PROPERTY_FACULTY;
        $extended_property_names[] = self::PROPERTY_TRAINING_ID;
        $extended_property_names[] = self::PROPERTY_TRAINING;
        $extended_property_names[] = self::PROPERTY_DEPARTMENT;
        $extended_property_names[] = self::PROPERTY_DEPARTMENT_ID;
        
        return parent::get_default_property_names($extended_property_names);
    }

    /**
     * Get the data class data manager
     * 
     * @return DataManagerInterface
     */
    public function get_data_manager()
    {
        // return DataManager :: getInstance();
    }

    /**
     * Returns the assignment_id of this EmploymentParts.
     * 
     * @return integer The assignment_id.
     */
    public function get_assignment_id()
    {
        return $this->get_default_property(self::PROPERTY_ASSIGNMENT_ID);
    }

    /**
     * Sets the assignment_id of this EmploymentParts.
     * 
     * @param integer $assignment_id
     */
    public function set_assignment_id($assignment_id)
    {
        $this->set_default_property(self::PROPERTY_ASSIGNMENT_ID, $assignment_id);
    }

    /**
     * Returns the hours of this EmploymentParts.
     * 
     * @return string The hours.
     */
    public function get_hours()
    {
        return $this->get_default_property(self::PROPERTY_HOURS);
    }

    /**
     * Sets the hours of this EmploymentParts.
     * 
     * @param string $hours
     */
    public function set_hours($hours)
    {
        $this->set_default_property(self::PROPERTY_HOURS, $hours);
    }

    /**
     * Returns the start_date of this EmploymentParts.
     * 
     * @return string The start_date.
     */
    public function get_start_date()
    {
        return $this->get_default_property(self::PROPERTY_START_DATE);
    }

    /**
     * Sets the start_date of this EmploymentParts.
     * 
     * @param string $start_date
     */
    public function set_start_date($start_date)
    {
        $this->set_default_property(self::PROPERTY_START_DATE, $start_date);
    }

    /**
     * Returns the end_date of this EmploymentParts.
     * 
     * @return string The end_date.
     */
    public function get_end_date()
    {
        return $this->get_default_property(self::PROPERTY_END_DATE);
    }

    /**
     * Sets the end_date of this EmploymentParts.
     * 
     * @param string $end_date
     */
    public function set_end_date($end_date)
    {
        $this->set_default_property(self::PROPERTY_END_DATE, $end_date);
    }

    /**
     * Returns the assignment_volume of this EmploymentParts.
     * 
     * @return string The assignment_volume.
     */
    public function get_assignment_volume()
    {
        return $this->get_default_property(self::PROPERTY_ASSIGNMENT_VOLUME);
    }

    /**
     * Sets the assignment_volume of this EmploymentParts.
     * 
     * @param string $assignment_volume
     */
    public function set_assignment_volume($assignment_volume)
    {
        $this->set_default_property(self::PROPERTY_ASSIGNMENT_VOLUME, $assignment_volume);
    }

    /**
     * Returns the volume of this EmploymentParts.
     * 
     * @return string The volume.
     */
    public function get_volume()
    {
        return $this->get_default_property(self::PROPERTY_VOLUME);
    }

    public function get_volume_hours()
    {
        if ($this->get_hours())
        {
            return $this->get_volume() . '% (' . $this->get_hours() . ')';
        }
        return $this->get_volume() . '%';
    }

    /**
     * Sets the volume of this EmploymentParts.
     * 
     * @param string $volume
     */
    public function set_volume($volume)
    {
        $this->set_default_property(self::PROPERTY_VOLUME, $volume);
    }

    /**
     * Returns the faculty_id of this EmploymentParts.
     * 
     * @return integer The faculty_id.
     */
    public function get_faculty_id()
    {
        return $this->get_default_property(self::PROPERTY_FACULTY_ID);
    }

    /**
     * Sets the faculty_id of this EmploymentParts.
     * 
     * @param integer $faculty_id
     */
    public function set_faculty_id($faculty_id)
    {
        $this->set_default_property(self::PROPERTY_FACULTY_ID, $faculty_id);
    }

    /**
     * Returns the faculty of this EmploymentParts.
     * 
     * @return string The faculty.
     */
    public function get_faculty()
    {
        return $this->get_default_property(self::PROPERTY_FACULTY);
    }

    /**
     * Sets the faculty of this EmploymentParts.
     * 
     * @param string $faculty
     */
    public function set_faculty($faculty)
    {
        $this->set_default_property(self::PROPERTY_FACULTY, $faculty);
    }

    /**
     * Returns the training_id of this EmploymentParts.
     * 
     * @return integer The training_id.
     */
    public function get_training_id()
    {
        return $this->get_default_property(self::PROPERTY_TRAINING_ID);
    }

    /**
     * Sets the training_id of this EmploymentParts.
     * 
     * @param integer $training_id
     */
    public function set_training_id($training_id)
    {
        $this->set_default_property(self::PROPERTY_TRAINING_ID, $training_id);
    }

    /**
     * Returns the training of this EmploymentParts.
     * 
     * @return string The training.
     */
    public function get_training()
    {
        return $this->get_default_property(self::PROPERTY_TRAINING);
    }

    /**
     * Sets the training of this EmploymentParts.
     * 
     * @param string $training
     */
    public function set_training($training)
    {
        $this->set_default_property(self::PROPERTY_TRAINING, $training);
    }

    public function get_department()
    {
        return $this->get_default_property(self::PROPERTY_DEPARTMENT);
    }

    public function set_department($department)
    {
        $this->set_default_property(self::PROPERTY_DEPARTMENT, $department);
    }

    public function get_department_id()
    {
        return $this->get_default_property(self::PROPERTY_DEPARTMENT_ID);
    }

    public function set_department_id($department_id)
    {
        $this->set_default_property(self::PROPERTY_DEPARTMENT_ID, $department_id);
    }

    /**
     *
     * @return string The table name of the data class
     */
    public static function get_table_name()
    {
        return Utilities::get_classname_from_namespace(self::class_name(), true);
    }
}
