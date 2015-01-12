<?php
namespace Application\Discovery\module\person;

use application\discovery\DiscoveryItem;

class Person extends DiscoveryItem
{
    const CLASS_NAME = __CLASS__;
    const PROPERTY_OFFICIAL_CODE = 'official_code';
    const PROPERTY_FIRST_NAME = 'first_name';
    const PROPERTY_LAST_NAME = 'last_name';
    const PROPERTY_EMAIL = 'email';

    /**
     *
     * @return string
     */
    public function get_email()
    {
        return $this->get_default_property(self :: PROPERTY_EMAIL);
    }

    /**
     *
     * @return string
     */
    public function get_first_name()
    {
        return $this->get_default_property(self :: PROPERTY_FIRST_NAME);
    }

    /**
     *
     * @param string $year
     */
    public function set_first_name($first_name)
    {
        $this->set_default_property(self :: PROPERTY_FIRST_NAME, $first_name);
    }

    /**
     *
     * @param string $name
     */
    public function set_email($email)
    {
        $this->set_default_property(self :: PROPERTY_EMAIL, $email);
    }

    /**
     *
     * @return string
     */
    public function get_last_name()
    {
        return $this->get_default_property(self :: PROPERTY_LAST_NAME);
    }

    /**
     *
     * @param string $year
     */
    public function set_last_name($last_name)
    {
        $this->set_default_property(self :: PROPERTY_LAST_NAME, $last_name);
    }

    /**
     *
     * @return string
     */
    public function get_official_code()
    {
        return $this->get_default_property(self :: PROPERTY_OFFICIAL_CODE);
    }

    /**
     *
     * @param string $official_code
     */
    public function set_official_code($official_code)
    {
        $this->set_default_property(self :: PROPERTY_OFFICIAL_CODE, $official_code);
    }

    /**
     *
     * @param multitype:string $extended_property_names
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_EMAIL;
        $extended_property_names[] = self :: PROPERTY_FIRST_NAME;
        $extended_property_names[] = self :: PROPERTY_LAST_NAME;
        $extended_property_names[] = self :: PROPERTY_OFFICIAL_CODE;
        
        return parent :: get_default_property_names($extended_property_names);
    }

    /**
     *
     * @return DataManagerInterface
     */
    public function get_data_manager()
    {
        // return DataManager :: get_instance();
    }

    /**
     *
     * @return string
     */
    public function __toString()
    {
        $string = array();
        return implode(' | ', $string);
    }
}
