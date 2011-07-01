<?php
namespace application\discovery\module\enrollment;

use application\discovery\DiscoveryDataManager;
use application\discovery\DiscoveryItem;

class Enrollment extends DiscoveryItem
{
    const CLASS_NAME = __CLASS__;

    const PROPERTY_YEAR = 'year';
    const PROPERTY_TRAINING = 'training';

    /**
     * @return string
     */
    function get_year()
    {
        return $this->get_default_property(self :: PROPERTY_YEAR);
    }

    /**
     * @return string
     */
    function get_training()
    {
        return $this->get_default_property(self :: PROPERTY_TRAINING);
    }

    /**
     * @param string $year
     */
    function set_year($year)
    {
        $this->set_default_property(self :: PROPERTY_YEAR, $year);
    }

    /**
     * @param string $training
     */
    function set_training($training)
    {
        $this->set_default_property(self :: PROPERTY_TRAINING, $training);
    }

    /**
     * @param multitype:string $extended_property_names
     */
    static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_YEAR;
        $extended_property_names[] = self :: PROPERTY_TRAINING;

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
        $string[] = $this->get_training();
        return implode(' | ', $string);
    }
}
?>