<?php
namespace application\discovery\module\faculty\implementation\bamaflex;

use application\discovery\DiscoveryItem;
use application\discovery\DiscoveryDataManager;

class Dean extends DiscoveryItem
{
    const CLASS_NAME = __CLASS__;
    const PROPERTY_SOURCE = 'source';
    const PROPERTY_FACULTY_ID = 'faculty_id';
    const PROPERTY_FUNCTION_ID = 'function_id';
    const PROPERTY_PERSON = 'person';
    const PROPERTY_FUNCTION = 'function';

    /**
     *
     * @return int
     */
    function get_source()
    {
        return $this->get_default_property(self :: PROPERTY_SOURCE);
    }

    /**
     *
     * @param int $source
     */
    function set_source($source)
    {
        $this->set_default_property(self :: PROPERTY_SOURCE, $source);
    }

    function get_faculty_id()
    {
        return $this->get_default_property(self :: PROPERTY_FACULTY_ID);
    }

    function set_faculty_id($faculty_id)
    {
        $this->set_default_property(self :: PROPERTY_FACULTY_ID, $faculty_id);
    }

    function get_function_id()
    {
        return $this->get_default_property(self :: PROPERTY_FUNCTION_ID);
    }

    function set_function_id($function_id)
    {
        $this->set_default_property(self :: PROPERTY_FUNCTION_ID, $function_id);
    }

    function get_person()
    {
        return $this->get_default_property(self :: PROPERTY_PERSON);
    }

    function set_person($person)
    {
        $this->set_default_property(self :: PROPERTY_PERSON, $person);
    }

    function get_function()
    {
        return $this->get_default_property(self :: PROPERTY_FUNCTION);
    }

    function set_function($function)
    {
        $this->set_default_property(self :: PROPERTY_FUNCTION, $function);
    }

    /**
     *
     * @param multitype:string $extended_property_names
     */
    static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_SOURCE;
        $extended_property_names[] = self :: PROPERTY_FACULTY_ID;
        $extended_property_names[] = self :: PROPERTY_FUNCTION_ID;
        $extended_property_names[] = self :: PROPERTY_PERSON;
        $extended_property_names[] = self :: PROPERTY_FUNCTION;

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
}
