<?php
namespace application\discovery\module\training_info\implementation\bamaflex;

use application\discovery\DiscoveryItem;

use application\discovery\DiscoveryDataManager;

class SubTrajectory extends DiscoveryItem
{
    const CLASS_NAME = __CLASS__;
    
    const PROPERTY_SOURCE = 'source';
    const PROPERTY_TRAJECTORY_ID = 'trajectory_id';
    const PROPERTY_NAME = 'name';
    
    private $courses;

    /**
     * @return int
     */
    function get_source()
    {
        return $this->get_default_property(self :: PROPERTY_SOURCE);
    }

    /**
     * @param int $source
     */
    function set_source($source)
    {
        $this->set_default_property(self :: PROPERTY_SOURCE, $source);
    }

    function get_trajectory_id()
    {
        return $this->get_default_property(self :: PROPERTY_TRAJECTORY_ID);
    }

    function set_trajectory_id($trajectory_id)
    {
        $this->set_default_property(self :: PROPERTY_TRAJECTORY_ID, $trajectory_id);
    }

    function get_name()
    {
        return $this->get_default_property(self :: PROPERTY_NAME);
    }

    function set_name($name)
    {
        $this->set_default_property(self :: PROPERTY_NAME, $name);
    }

    static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_SOURCE;
        $extended_property_names[] = self :: PROPERTY_TRAJECTORY_ID;
        $extended_property_names[] = self :: PROPERTY_NAME;
        
        return parent :: get_default_property_names($extended_property_names);
    }

    /**
     * @return DiscoveryDataManagerInterface
     */
    function get_data_manager()
    {
        return DiscoveryDataManager :: get_instance();
    }

    function get_courses()
    {
        return $this->courses;
    }

    function set_courses($courses)
    {
        $this->courses = $courses;
    }

    function has_courses()
    {
        return count($this->courses) > 0;
    }

    function add_course($course)
    {
        $this->courses[] = $course;
    }
}
?>