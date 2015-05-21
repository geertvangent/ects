<?php
namespace Ehb\Application\Discovery\Module\Profile\Implementation\Chamilo;

class Profile extends \Ehb\Application\Discovery\Module\Profile\Profile
{
    const CLASS_NAME = __CLASS__;
    const PROPERTY_USERNAME = 'username';
    const PROPERTY_TIMEZONE = 'timezone';

    /**
     *
     * @return int
     */
    public function get_username()
    {
        return $this->get_default_property(self :: PROPERTY_USERNAME);
    }

    /**
     *
     * @return Birth
     */
    public function get_timezone()
    {
        return $this->get_default_property(self :: PROPERTY_TIMEZONE);
    }

    /**
     *
     * @param int $username
     */
    public function set_username($username)
    {
        $this->set_default_property(self :: PROPERTY_USERNAME, $username);
    }

    /**
     *
     * @param string $timezone
     */
    public function set_timezone($timezone)
    {
        $this->set_default_property(self :: PROPERTY_TIMEZONE, $timezone);
    }

    /**
     *
     * @param multitype:string $extended_property_names
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_USERNAME;
        $extended_property_names[] = self :: PROPERTY_TIMEZONE;
        
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
}
