<?php
namespace Ehb\Application\Discovery\Module\Elo\Implementation\Chamilo\Type;

use Ehb\Application\Discovery\Module\Elo\Implementation\Chamilo\TypeData;

class ContentObjectData extends TypeData
{
    const PROPERTY_PLATFORM = 'platform';
    const PROPERTY_OBJECT_TYPE = 'object_type';
    const PROPERTY_NAME = 'name';
    const PROPERTY_USER_ID = 'user_id';
    const PROPERTY_USER_OFFICIAL_CODE = 'user_official_code';
    const PROPERTY_USER_EMAIL = 'user_email';

    public static function get_filters($filters = array())
    {
        $filters[] = self::PROPERTY_PLATFORM;
        $filters[] = self::PROPERTY_OBJECT_TYPE;
        
        return parent::get_filters($filters);
    }

    public function get_platform()
    {
        return $this->get_default_property(self::PROPERTY_PLATFORM);
    }

    public function set_platform($platform)
    {
        $this->set_default_property(self::PROPERTY_PLATFORM, $platform);
    }

    public function get_object_type()
    {
        return $this->get_default_property(self::PROPERTY_OBJECT_TYPE);
    }

    public function set_object_type($object_type)
    {
        $this->set_default_property(self::PROPERTY_OBJECT_TYPE, $object_type);
    }

    public function get_name()
    {
        return $this->get_default_property(self::PROPERTY_NAME);
    }

    public function set_name($name)
    {
        $this->set_default_property(self::PROPERTY_NAME, $name);
    }

    public function get_user_id()
    {
        return $this->get_default_property(self::PROPERTY_USER_ID);
    }

    public function set_user_id($user_id)
    {
        $this->set_default_property(self::PROPERTY_USER_ID, $user_id);
    }

    public function get_user_official_code()
    {
        return $this->get_default_property(self::PROPERTY_USER_OFFICIAL_CODE);
    }

    public function set_user_official_code($user_official_code)
    {
        $this->set_default_property(self::PROPERTY_USER_OFFICIAL_CODE, $user_official_code);
    }

    public function get_user_email()
    {
        return $this->get_default_property(self::PROPERTY_USER_EMAIL);
    }

    public function set_user_email($user_email)
    {
        $this->set_default_property(self::PROPERTY_USER_EMAIL, $user_email);
    }

    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self::PROPERTY_PLATFORM;
        $extended_property_names[] = self::PROPERTY_OBJECT_TYPE;
        $extended_property_names[] = self::PROPERTY_NAME;
        $extended_property_names[] = self::PROPERTY_USER_ID;
        $extended_property_names[] = self::PROPERTY_USER_OFFICIAL_CODE;
        $extended_property_names[] = self::PROPERTY_USER_EMAIL;
        
        return parent::get_default_property_names($extended_property_names);
    }
}
