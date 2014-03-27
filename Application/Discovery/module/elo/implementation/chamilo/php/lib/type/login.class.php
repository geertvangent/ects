<?php
namespace application\discovery\module\elo\implementation\chamilo;

use common\libraries\DataClass;

class LoginData extends DataClass
{
    const CLASS_NAME = __CLASS__;
    const PROPERTY_PLATFORM = 'platform';
    const PROPERTY_USER_ID = 'user_id';
    const PROPERTY_USER_OFFICIAL_CODE = 'user_official_code';
    const PROPERTY_USER_EMAIL = 'user_email';

    public static function get_filters($filters = array())
    {
        $filters[] = self :: PROPERTY_PLATFORM;

        return parent :: get_filters($filters);
    }

    public function get_platform()
    {
        return $this->get_default_property(self :: PROPERTY_PLATFORM);
    }

    public function set_platform($platform)
    {
        $this->set_default_property(self :: PROPERTY_PLATFORM, $platform);
    }

    public function get_user_id()
    {
        return $this->get_default_property(self :: PROPERTY_USER_ID);
    }

    public function set_user_id($user_id)
    {
        $this->set_default_property(self :: PROPERTY_USER_ID, $user_id);
    }

    public function get_user_official_code()
    {
        return $this->get_default_property(self :: PROPERTY_USER_OFFICIAL_CODE);
    }

    public function set_user_official_code($user_official_code)
    {
        $this->set_default_property(self :: PROPERTY_USER_OFFICIAL_CODE, $user_official_code);
    }

    public function get_user_email()
    {
        return $this->get_default_property(self :: PROPERTY_USER_EMAIL);
    }

    public function set_user_email($user_email)
    {
        $this->set_default_property(self :: PROPERTY_USER_EMAIL, $user_email);
    }

    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_PLATFORM;
        $extended_property_names[] = self :: PROPERTY_USER_ID;
        $extended_property_names[] = self :: PROPERTY_USER_OFFICIAL_CODE;
        $extended_property_names[] = self :: PROPERTY_USER_EMAIL;

        return parent :: get_default_property_names($extended_property_names);
    }
}
