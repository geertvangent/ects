<?php
namespace application\discovery\module\profile\implementation\chamilo;

use application\discovery\DiscoveryDataManager;

class Profile extends \application\discovery\module\profile\Profile
{
    const CLASS_NAME = __CLASS__;

    const PROPERTY_USERNAME = 'username';
    const PROPERTY_TIMEZONE = 'timezone';

    /**
     * @return int
     */
    function get_username()
    {
        return $this->get_default_property(self :: PROPERTY_USERNAME);
    }

    /**
     * @return Birth
     */
    function get_timezone()
    {
        return $this->get_default_property(self :: PROPERTY_TIMEZONE);
    }

    /**
     * @param int $username
     */
    function set_username($username)
    {
        $this->set_default_property(self :: PROPERTY_USERNAME, $username);
    }

    /**
     * @param string $timezone
     */
    function set_timezone($timezone)
    {
        $this->set_default_property(self :: PROPERTY_TIMEZONE, $timezone);
    }

    /**
     * @param multitype:string $extended_property_names
     */
    static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_USERNAME;
        $extended_property_names[] = self :: PROPERTY_TIMEZONE;

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