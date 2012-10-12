<?php
namespace application\discovery\module\training_info\implementation\bamaflex;

use application\discovery\DiscoveryItem;

use application\discovery\DiscoveryDataManager;

class PackageCourse extends DiscoveryItem
{
    const CLASS_NAME = __CLASS__;
    
    const PROPERTY_SOURCE = 'source';
    const PROPERTY_NAME = 'name';
    const PROPERTY_TRAJECTORY_PART = 'trajectory_part';
    const PROPERTY_CREDITS = 'credits';
    const PROPERTY_PACKAGE_ID = 'package_id';
    const PROPERTY_PROGRAMME_ID = 'programme_id';
    const PROPERTY_PARENT_PROGRAMME_ID = 'parent_programme_id';
    
    private $children;

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

    function get_credits()
    {
        return $this->get_default_property(self :: PROPERTY_CREDITS);
    }

    function set_credits($credits)
    {
        $this->set_default_property(self :: PROPERTY_CREDITS, $credits);
    }

    function get_trajectory_part()
    {
        return $this->get_default_property(self :: PROPERTY_TRAJECTORY_PART);
    }

    function set_trajectory_part($trajectory_part)
    {
        $this->set_default_property(self :: PROPERTY_TRAJECTORY_PART, $trajectory_part);
    }

    function get_package_id()
    {
        return $this->get_default_property(self :: PROPERTY_PACKAGE_ID);
    }

    function set_package_id($package_id)
    {
        $this->set_default_property(self :: PROPERTY_PACKAGE_ID, $package_id);
    }

    function get_programme_id()
    {
        return $this->get_default_property(self :: PROPERTY_PROGRAMME_ID);
    }

    function set_programme_id($programme_id)
    {
        $this->set_default_property(self :: PROPERTY_PROGRAMME_ID, $programme_id);
    }

    function get_name()
    {
        return $this->get_default_property(self :: PROPERTY_NAME);
    }

    function set_name($name)
    {
        $this->set_default_property(self :: PROPERTY_NAME, $name);
    }

    function get_children()
    {
        return $this->children;
    }

    function set_children($children)
    {
        $this->children = $children;
    }

    function add_child($child)
    {
        $this->children[] = $child;
    }

    function has_children()
    {
        return count($this->children) > 0;
    }

    function get_parent_programme_id()
    {
        return $this->get_default_property(self :: PROPERTY_PARENT_PROGRAMME_ID);
    }

    function set_parent_programme_id($parent_programme_id)
    {
        $this->set_default_property(self :: PROPERTY_PARENT_PROGRAMME_ID, $parent_programme_id);
    }

    static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_SOURCE;
        $extended_property_names[] = self :: PROPERTY_PACKAGE_ID;
        $extended_property_names[] = self :: PROPERTY_NAME;
        $extended_property_names[] = self :: PROPERTY_PROGRAMME_ID;
        $extended_property_names[] = self :: PROPERTY_CREDITS;
        $extended_property_names[] = self :: PROPERTY_TRAJECTORY_PART;
        $extended_property_names[] = self :: PROPERTY_PARENT_PROGRAMME_ID;
        
        return parent :: get_default_property_names($extended_property_names);
    }

    /**
     * @return DiscoveryDataManagerInterface
     */
    function get_data_manager()
    {
        return DiscoveryDataManager :: get_instance();
    }
}
?>