<?php
namespace application\discovery\module\course\implementation\bamaflex;

use application\discovery\DiscoveryDataManager;

class CompetenceStructured extends Competence
{
    const CLASS_NAME = __CLASS__;
    
    const PROPERTY_PROGRAMME_ID = 'programme_id';
    const PROPERTY_CODE = 'code';
    const PROPERTY_SUMMARY = 'summary';
    const PROPERTY_LEVEL = 'level';

    function get_programme_id()
    {
        return $this->get_default_property(self :: PROPERTY_PROGRAMME_ID);
    }

    function set_programme_id($programme_id)
    {
        $this->set_default_property(self :: PROPERTY_PROGRAMME_ID, $programme_id);
    }

    function get_code()
    {
        return $this->get_default_property(self :: PROPERTY_CODE);
    }

    function set_code($code)
    {
        $this->set_default_property(self :: PROPERTY_CODE, $code);
    }

    function get_summary()
    {
        return $this->get_default_property(self :: PROPERTY_SUMMARY);
    }

    function set_summary($summary)
    {
        $this->set_default_property(self :: PROPERTY_SUMMARY, $summary);
    }

    function get_level()
    {
        return $this->get_default_property(self :: PROPERTY_LEVEL);
    }

    function set_level($level)
    {
        $this->set_default_property(self :: PROPERTY_LEVEL, $level);
    }

    /**
     * @param multitype:string $extended_property_names
     */
    static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_PROGRAMME_ID;
        $extended_property_names[] = self :: PROPERTY_CODE;
        $extended_property_names[] = self :: PROPERTY_SUMMARY;
        $extended_property_names[] = self :: PROPERTY_LEVEL;
        
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