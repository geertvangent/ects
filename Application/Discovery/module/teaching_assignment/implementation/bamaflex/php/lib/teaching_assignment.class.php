<?php
namespace application\discovery\module\teaching_assignment\implementation\bamaflex;

use application\discovery\DiscoveryDataManager;

class TeachingAssignment extends \application\discovery\module\teaching_assignment\TeachingAssignment
{
    const CLASS_NAME = __CLASS__;
    
    const PROPERTY_SOURCE = 'source';
    const PROPERTY_FACULTY = 'faculty';
    const PROPERTY_TRAJECTORY_PART = 'trajectory_part';
    const PROPERTY_CREDITS = 'credits';
    const PROPERTY_WEIGHT = 'weight';

    const SOURCE_MANAGER = 1;
    const SOURCE_TEACHER = 2;

    /**
     * @return int
     */
    function get_source()
    {
        return $this->get_default_property(self :: PROPERTY_SOURCE);
    }

    /**
     * @return string
     */
    function get_faculty()
    {
        return $this->get_default_property(self :: PROPERTY_FACULTY);
    }

    /**
     * Returns the trajectory_part of this TeachingAssignment.
     * @return string The trajectory_part.
     */
    function get_trajectory_part()
    {
        return $this->get_default_property(self :: PROPERTY_TRAJECTORY_PART);
    }

    /**
     * Sets the trajectory_part of this TeachingAssignment.
     * @param string $trajectory_part
     */
    function set_trajectory_part($trajectory_part)
    {
        $this->set_default_property(self :: PROPERTY_TRAJECTORY_PART, $trajectory_part);
    }

    /**
     * Returns the credits of this TeachingAssignment.
     * @return int The credits.
     */
    function get_credits()
    {
        return $this->get_default_property(self :: PROPERTY_CREDITS);
    }

    /**
     * Sets the credits of this TeachingAssignment.
     * @param int $credits
     */
    function set_credits($credits)
    {
        $this->set_default_property(self :: PROPERTY_CREDITS, $credits);
    }

    /**
     * Returns the weight of this TeachingAssignment.
     * @return int The weight.
     */
    function get_weight()
    {
        return $this->get_default_property(self :: PROPERTY_WEIGHT);
    }

    /**
     * Sets the weight of this TeachingAssignment.
     * @param int $weight
     */
    function set_weight($weight)
    {
        $this->set_default_property(self :: PROPERTY_WEIGHT, $weight);
    }

    /**
     * @param int $source
     */
    function set_source($source)
    {
        $this->set_default_property(self :: PROPERTY_SOURCE, $source);
    }

    /**
     * @param string $faculty
     */
    function set_faculty($faculty)
    {
        $this->set_default_property(self :: PROPERTY_FACULTY, $faculty);
    }

    /**
     * @param multitype:string $extended_property_names
     */
    static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_SOURCE;
        $extended_property_names[] = self :: PROPERTY_FACULTY;
        $extended_property_names[] = self :: PROPERTY_TRAJECTORY_PART;
        $extended_property_names[] = self :: PROPERTY_CREDITS;
        $extended_property_names[] = self :: PROPERTY_WEIGHT;
        
        return parent :: get_default_property_names($extended_property_names);
    }

    /**
     * @return DiscoveryDataManagerInterface
     */
    function get_data_manager()
    {
        return DiscoveryDataManager :: get_instance();
    }

    /**
     * @return string
     */
    function __toString()
    {
        $string = array();
        $string[] = $this->get_year();
        $string[] = $this->get_faculty();
        $string[] = $this->get_training();
        $string[] = $this->get_name();
        
        return implode(' | ', $string);
    }
}
?>