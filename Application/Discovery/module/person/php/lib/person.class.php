<?php
namespace application\discovery\module\person;

use application\discovery\DiscoveryDataManager;
use application\discovery\DiscoveryItem;

class Person extends DiscoveryItem
{
    const CLASS_NAME = __CLASS__;
    
    const PROPERTY_OFFICIAL_CODE = 'official_code';
    const PROPERTY_FIRST_NAME = 'first_name';
    const PROPERTY_LAST_NAME = 'last_name';
    const PROPERTY_EMAIL = 'email';

    /**
     * @return string
     */
    function get_email()
    {
        return $this->get_default_property(self :: PROPERTY_EMAIL);
    }

    /**
     * @return string
     */
    function get_first_name()
    {
        return $this->get_default_property(self :: PROPERTY_FIRST_NAME);
    }

    /**
     * @param string $year
     */
    function set_first_name($first_name)
    {
        $this->set_default_property(self :: PROPERTY_FIRST_NAME, $first_name);
    }

    /**
     * @param string $name
     */
    function set_email($email)
    {
        $this->set_default_property(self :: PROPERTY_EMAIL, $email);
    }

    /**
     * @return string
     */
    function get_last_name()
    {
        return $this->get_default_property(self :: PROPERTY_LAST_NAME);
    }

    /**
     * @param string $year
     */
    function set_last_name($last_name)
    {
        $this->set_default_property(self :: PROPERTY_LAST_NAME, $last_name);
    }

    /**
     * @return string
     */
    function get_official_code()
    {
        return $this->get_default_property(self :: PROPERTY_OFFICIAL_CODE);
    }

    /**
     * @param string $official_code
     */
    function set_official_code($official_code)
    {
        $this->set_default_property(self :: PROPERTY_OFFICIAL_CODE, $official_code);
    }

    /**
     * @param multitype:string $extended_property_names
     */
    static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_EMAIL;
        $extended_property_names[] = self :: PROPERTY_FIRST_NAME;
        $extended_property_names[] = self :: PROPERTY_LAST_NAME;
        $extended_property_names[] = self :: PROPERTY_OFFICIAL_CODE;
        
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