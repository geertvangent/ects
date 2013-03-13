<?php
namespace application\discovery\module\teaching_assignment\implementation\bamaflex;

class TeachingAssignment extends \application\discovery\module\teaching_assignment\TeachingAssignment
{
    const CLASS_NAME = __CLASS__;
    const PROPERTY_SOURCE = 'source';
    const PROPERTY_FACULTY = 'faculty';
    const PROPERTY_FACULTY_ID = 'faculty_id';
    const PROPERTY_TRAJECTORY_PART = 'trajectory_part';
    const PROPERTY_CREDITS = 'credits';
    const PROPERTY_WEIGHT = 'weight';
    const PROPERTY_TIMEFRAME_ID = 'timeframe_id';
    const PROPERTY_PROGRAMME_ID = 'programme_id';
    const PROPERTY_MANAGER = 'manager';
    const PROPERTY_TEACHER = 'teacher';
    const TYPE_COORDINATOR_YES = 1;
    const TYPE_COORDINATOR_NO = 0;
    const TYPE_NONE = 2;
    const TIMEFRAME_ACADEMIC_YEAR = '1';
    const TIMEFRAME_FIRST_TERM = '2';
    const TIMEFRAME_SECOND_TERM = '3';
    const TIMEFRAME_BOTH_TERMS = '4';
    const TIMEFRAME_UNKNOWN = '5';

    /**
     *
     * @return int
     */
    public function get_source()
    {
        return $this->get_default_property(self :: PROPERTY_SOURCE);
    }

    public function get_type()
    {
        return $this->get_default_property(self :: PROPERTY_TYPE);
    }

    /**
     *
     * @return string
     */
    public function get_faculty()
    {
        return $this->get_default_property(self :: PROPERTY_FACULTY);
    }

    /**
     *
     * @return integer
     */
    public function get_faculty_id()
    {
        return $this->get_default_property(self :: PROPERTY_FACULTY_ID);
    }

    /**
     *
     * @return integer
     */
    public function get_timeframe_id()
    {
        return $this->get_default_property(self :: PROPERTY_TIMEFRAME_ID);
    }

    /**
     * Returns the trajectory_part of this TeachingAssignment.
     * 
     * @return string The trajectory_part.
     */
    public function get_trajectory_part()
    {
        return $this->get_default_property(self :: PROPERTY_TRAJECTORY_PART);
    }

    /**
     * Sets the trajectory_part of this TeachingAssignment.
     * 
     * @param string $trajectory_part
     */
    public function set_trajectory_part($trajectory_part)
    {
        $this->set_default_property(self :: PROPERTY_TRAJECTORY_PART, $trajectory_part);
    }

    /**
     * Returns the credits of this TeachingAssignment.
     * 
     * @return int The credits.
     */
    public function get_credits()
    {
        return $this->get_default_property(self :: PROPERTY_CREDITS);
    }

    /**
     * Returns the programme_id of this TeachingAssignment.
     * 
     * @return int programme_id.
     */
    public function get_programme_id()
    {
        return $this->get_default_property(self :: PROPERTY_PROGRAMME_ID);
    }

    public function get_teacher()
    {
        return $this->get_default_property(self :: PROPERTY_TEACHER);
    }

    public function get_manager()
    {
        return $this->get_default_property(self :: PROPERTY_MANAGER);
    }

    /**
     * Sets the credits of this TeachingAssignment.
     * 
     * @param int $credits
     */
    public function set_credits($credits)
    {
        $this->set_default_property(self :: PROPERTY_CREDITS, $credits);
    }

    /**
     * Returns the weight of this TeachingAssignment.
     * 
     * @return int The weight.
     */
    public function get_weight()
    {
        return $this->get_default_property(self :: PROPERTY_WEIGHT);
    }

    /**
     * Sets the weight of this TeachingAssignment.
     * 
     * @param int $weight
     */
    public function set_weight($weight)
    {
        $this->set_default_property(self :: PROPERTY_WEIGHT, $weight);
    }

    /**
     *
     * @param int $source
     */
    public function set_source($source)
    {
        $this->set_default_property(self :: PROPERTY_SOURCE, $source);
    }

    public function set_type($type)
    {
        $this->set_default_property(self :: PROPERTY_TYPE, $type);
    }

    /**
     *
     * @param string $faculty
     */
    public function set_faculty($faculty)
    {
        $this->set_default_property(self :: PROPERTY_FACULTY, $faculty);
    }

    /**
     *
     * @param integer $faculty_id
     */
    public function set_faculty_id($faculty_id)
    {
        $this->set_default_property(self :: PROPERTY_FACULTY_ID, $faculty_id);
    }

    /**
     *
     * @param string $timeframe_id
     */
    public function set_timeframe_id($timeframe_id)
    {
        $this->set_default_property(self :: PROPERTY_TIMEFRAME_ID, $timeframe_id);
    }

    /**
     *
     * @param string $programme_id
     */
    public function set_programme_id($programme_id)
    {
        $this->set_default_property(self :: PROPERTY_PROGRAMME_ID, $programme_id);
    }

    public function set_teacher($teacher)
    {
        $this->set_default_property(self :: PROPERTY_TEACHER, $teacher);
    }

    public function set_manager($manager)
    {
        $this->set_default_property(self :: PROPERTY_MANAGER, $manager);
    }

    /**
     *
     * @param multitype:string $extended_property_names
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_SOURCE;
        $extended_property_names[] = self :: PROPERTY_FACULTY;
        $extended_property_names[] = self :: PROPERTY_FACULTY_ID;
        $extended_property_names[] = self :: PROPERTY_TRAJECTORY_PART;
        $extended_property_names[] = self :: PROPERTY_CREDITS;
        $extended_property_names[] = self :: PROPERTY_WEIGHT;
        $extended_property_names[] = self :: PROPERTY_TIMEFRAME_ID;
        $extended_property_names[] = self :: PROPERTY_PROGRAMME_ID;
        $extended_property_names[] = self :: PROPERTY_MANAGER;
        $extended_property_names[] = self :: PROPERTY_TEACHER;
        
        return parent :: get_default_property_names($extended_property_names);
    }

    /**
     *
     * @return string
     */
    public function get_timeframe()
    {
        return self :: timeframe($this->get_timeframe_id());
    }

    /**
     *
     * @return string
     */
    public static function timeframe($timeframe_id)
    {
        switch ($timeframe_id)
        {
            case self :: TIMEFRAME_ACADEMIC_YEAR :
                return 'AcademicYear';
                break;
            case self :: TIMEFRAME_FIRST_TERM :
                return 'FirstTerm';
                break;
            case self :: TIMEFRAME_SECOND_TERM :
                return 'SecondTerm';
                break;
            case self :: TIMEFRAME_BOTH_TERMS :
                return 'BothTerms';
                break;
            case self :: TIMEFRAME_UNKNOWN :
                return 'Unknown';
                break;
        }
    }

    /**
     *
     * @return string
     */
    public function get_manager_type()
    {
        return self :: manager_type($this->get_manager());
    }

    /**
     *
     * @return string
     */
    public static function manager_type($type)
    {
        switch ($type)
        {
            case self :: TYPE_COORDINATOR_NO :
                return 'ManagerCoordinatorNo';
                break;
            case self :: TYPE_COORDINATOR_YES :
                return 'ManagerCoordinatorYes';
                break;
            case self :: TYPE_NONE :
                return 'ManagerNone';
                break;
            default :
                return 'ManagerNone';
                break;
        }
    }

    /**
     *
     * @return string
     */
    public function get_teacher_type()
    {
        return self :: teacher_type($this->get_teacher());
    }

    /**
     *
     * @return string
     */
    public static function teacher_type($type)
    {
        switch ($type)
        {
            case self :: TYPE_COORDINATOR_NO :
                return 'TeacherCoordinatorNo';
                break;
            case self :: TYPE_COORDINATOR_YES :
                return 'TeacherCoordinatorYes';
                break;
            case self :: TYPE_NONE :
                return 'TeacherNone';
                break;
            default :
                return 'TeacherNone';
                break;
        }
    }

    /**
     *
     * @return DataManagerInterface
     */
    public function get_data_manager()
    {
        // return DataManager :: get_instance();
    }

    /**
     *
     * @return string
     */
    public function __toString()
    {
        $string = array();
        $string[] = $this->get_year();
        $string[] = $this->get_faculty();
        $string[] = $this->get_training();
        $string[] = $this->get_name();
        
        return implode(' | ', $string);
    }
}
