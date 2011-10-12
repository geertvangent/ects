<?php
namespace application\discovery\module\course\implementation\bamaflex;

use application\discovery\DiscoveryDataManager;

class EvaluationStructured extends Evaluation
{
    const CLASS_NAME = __CLASS__;
    
    const PROPERTY_PROGRAMME_ID = 'programme_id';
    const PROPERTY_TRY = 'try';
    const PROPERTY_MOMENT_ID = 'moment_id';
    const PROPERTY_MOMENT = 'moment';
    const PROPERTY_TYPE_ID = 'type_id';
    const PROPERTY_TYPE = 'type';
    const PROPERTY_PERMANENT = 'permanent';
    const PROPERTY_PERCENTAGE = 'percentage';

    function get_programme_id()
    {
        return $this->get_default_property(self :: PROPERTY_PROGRAMME_ID);
    }

    function set_programme_id($programme_id)
    {
        $this->set_default_property(self :: PROPERTY_PROGRAMME_ID, $programme_id);
    }

    function get_try()
    {
        return $this->get_default_property(self :: PROPERTY_TRY);
    }

    function set_try($try)
    {
        $this->set_default_property(self :: PROPERTY_TRY, $try);
    }

    function get_moment_id()
    {
        return $this->get_default_property(self :: PROPERTY_MOMENT_ID);
    }

    function set_moment_id($moment_id)
    {
        $this->set_default_property(self :: PROPERTY_MOMENT_ID, $moment_id);
    }

    function get_moment()
    {
        return $this->get_default_property(self :: PROPERTY_MOMENT);
    }

    function set_moment($moment)
    {
        $this->set_default_property(self :: PROPERTY_MOMENT, $moment);
    }

    function get_type_id()
    {
        return $this->get_default_property(self :: PROPERTY_TYPE_ID);
    }

    function set_type_id($type_id)
    {
        $this->set_default_property(self :: PROPERTY_TYPE_ID, $type_id);
    }

    function get_type()
    {
        return $this->get_default_property(self :: PROPERTY_TYPE);
    }

    function set_type($type)
    {
        $this->set_default_property(self :: PROPERTY_TYPE, $type);
    }
    
    function get_permanent()
    {
        return $this->get_default_property(self :: PROPERTY_PERMANENT);
    }

    function set_permanent($permanent)
    {
        $this->set_default_property(self :: PROPERTY_PERMANENT, $permanent);
    }
    
    function get_percentage()
    {
        return $this->get_default_property(self :: PROPERTY_PERCENTAGE);
    }

    function set_percentage($percentage)
    {
        $this->set_default_property(self :: PROPERTY_PERCENTAGE, $percentage);
    }
    

    /**
     * @param multitype:string $extended_property_names
     */
    static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_PROGRAMME_ID;
        $extended_property_names[] = self :: PROPERTY_MOMENT_ID;
        $extended_property_names[] = self :: PROPERTY_MOMENT;
        $extended_property_names[] = self :: PROPERTY_TRY;
        $extended_property_names[] = self :: PROPERTY_TYPE;
        $extended_property_names[] = self :: PROPERTY_TYPE_ID;
        $extended_property_names[] = self :: PROPERTY_PERCENTAGE;
        $extended_property_names[] = self :: PROPERTY_PERMANENT;
        
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
        return implode(' | ', $string);
    }
}
?>