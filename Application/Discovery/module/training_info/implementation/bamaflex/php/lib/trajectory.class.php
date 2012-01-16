<?php
namespace application\discovery\module\training_info\implementation\bamaflex;

use application\discovery\DiscoveryItem;

use application\discovery\DiscoveryDataManager;

class Trajectory extends DiscoveryItem
{
    const CLASS_NAME = __CLASS__;
    
    const PROPERTY_SOURCE = 'source';
    const PROPERTY_TRAINING_ID = 'training_id';
    const PROPERTY_NAME = 'name';
    
    private $trajectories;

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

    function get_training_id()
    {
        return $this->get_default_property(self :: PROPERTY_TRAINING_ID);
    }

    function set_training_id($training_id)
    {
        $this->set_default_property(self :: PROPERTY_TRAINING_ID, $training_id);
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
        $extended_property_names[] = self :: PROPERTY_TRAINING_ID;
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

    function get_trajectories()
    {
        return $this->trajectories;
    }

    function set_trajectories($trajectories)
    {
        $this->trajectories = $trajectories;
    }

    function has_trajectories()
    {
        return count($this->trajectories) > 0;
    }

    function add_trajectory($trajectory)
    {
        $this->trajectories[] = $trajectory;
    }
}
?>