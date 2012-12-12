<?php
namespace application\discovery\module\teaching_assignment;

use application\discovery\DiscoveryDataManager;
use application\discovery\DiscoveryItem;

class TeachingAssignment extends DiscoveryItem
{
    const CLASS_NAME = __CLASS__;
    const PROPERTY_YEAR = 'year';
    const PROPERTY_TRAINING = 'training';
    const PROPERTY_TRAINING_ID = 'training_id';
    const PROPERTY_NAME = 'name';

    /**
     *
     * @return string
     */
    function get_year()
    {
        return $this->get_default_property(self :: PROPERTY_YEAR);
    }

    /**
     *
     * @return string
     */
    function get_training()
    {
        return $this->get_default_property(self :: PROPERTY_TRAINING);
    }

    /**
     *
     * @return integer
     */
    function get_training_id()
    {
        return $this->get_default_property(self :: PROPERTY_TRAINING_ID);
    }

    /**
     *
     * @return string
     */
    function get_name()
    {
        return $this->get_default_property(self :: PROPERTY_NAME);
    }

    /**
     *
     * @param string $year
     */
    function set_year($year)
    {
        $this->set_default_property(self :: PROPERTY_YEAR, $year);
    }

    /**
     *
     * @param string $training
     */
    function set_training($training)
    {
        $this->set_default_property(self :: PROPERTY_TRAINING, $training);
    }

    /**
     *
     * @param string $training_id
     */
    function set_training_id($training_id)
    {
        $this->set_default_property(self :: PROPERTY_TRAINING_ID, $training_id);
    }

    /**
     *
     * @param string $name
     */
    function set_name($name)
    {
        $this->set_default_property(self :: PROPERTY_NAME, $name);
    }

    /**
     *
     * @param multitype:string $extended_property_names
     */
    static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_YEAR;
        $extended_property_names[] = self :: PROPERTY_TRAINING;
        $extended_property_names[] = self :: PROPERTY_TRAINING_ID;
        $extended_property_names[] = self :: PROPERTY_NAME;
        
        return parent :: get_default_property_names($extended_property_names);
    }

    /**
     *
     * @return DiscoveryDataManagerInterface
     */
    function get_data_manager()
    {
        return DiscoveryDataManager :: get_instance();
    }

    /**
     *
     * @return string
     */
    function __toString()
    {
        $string = array();
        $string[] = $this->get_year();
        $string[] = $this->get_training();
        $string[] = $this->get_name();
        return implode(' | ', $string);
    }
}
?>