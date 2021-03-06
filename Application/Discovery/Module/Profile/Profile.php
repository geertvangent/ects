<?php
namespace Ehb\Application\Discovery\Module\Profile;

use Ehb\Application\Discovery\DiscoveryItem;

class Profile extends DiscoveryItem
{
    const PROPERTY_NAME = 'name';
    const PROPERTY_IDENTIFICATION_CODE = 'identification_code';
    const PROPERTY_EMAIL = 'email';
    const PROPERTY_COMMUNICATION = 'communication';
    const PROPERTY_LANGUAGE = 'language';
    const PROPERTY_PHOTO = 'photo';

    /**
     *
     * @return Name
     */
    public function get_name()
    {
        return $this->get_default_property(self::PROPERTY_NAME);
    }

    /**
     *
     * @return multitype:IdentificationCode
     */
    public function get_identification_code()
    {
        return $this->get_default_property(self::PROPERTY_IDENTIFICATION_CODE);
    }

    /**
     *
     * @return multitype:Email
     */
    public function get_email()
    {
        return $this->get_default_property(self::PROPERTY_EMAIL);
    }

    /**
     *
     * @return multitype:Communication
     */
    public function get_communication()
    {
        return $this->get_default_property(self::PROPERTY_COMMUNICATION);
    }

    /**
     *
     * @return string
     */
    public function get_language()
    {
        return $this->get_default_property(self::PROPERTY_LANGUAGE);
    }

    /**
     *
     * @return Photo
     */
    public function get_photo()
    {
        return $this->get_default_property(self::PROPERTY_PHOTO);
    }

    /**
     *
     * @param Name $name
     */
    public function set_name(Name $name)
    {
        $this->set_default_property(self::PROPERTY_NAME, $name);
    }

    /**
     *
     * @param multitype:IdentificationCode $code
     */
    public function set_identification_code($identification_code)
    {
        $this->set_default_property(self::PROPERTY_IDENTIFICATION_CODE, $identification_code);
    }

    /**
     *
     * @param multitype:Email $email
     */
    public function set_email($email)
    {
        $this->set_default_property(self::PROPERTY_EMAIL, $email);
    }

    /**
     *
     * @param multitype:Communication $communication
     */
    public function set_communication($communication)
    {
        $this->set_default_property(self::PROPERTY_COMMUNICATION, $communication);
    }

    /**
     *
     * @param string $language
     */
    public function set_language($language)
    {
        $this->set_default_property(self::PROPERTY_LANGUAGE, $language);
    }

    /**
     *
     * @param Photo $photo
     */
    public function set_photo(Photo $photo)
    {
        $this->set_default_property(self::PROPERTY_PHOTO, $photo);
    }

    /**
     *
     * @param IdentificationCode $code
     */
    public function add_identification_code(IdentificationCode $code)
    {
        $codes = $this->get_identification_code();
        $codes[] = $code;
        $this->set_identification_code($codes);
    }

    /**
     *
     * @param Communication $communication
     */
    public function add_communication(Communication $communication)
    {
        $communications = $this->get_communication();
        $communications[] = $communication;
        $this->set_communication($communications);
    }

    /**
     *
     * @param Email $email
     */
    public function add_email(Email $email)
    {
        $emails = $this->get_email();
        $emails[] = $email;
        $this->set_email($emails);
    }

    /**
     *
     * @param multitype:string $extended_property_names
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self::PROPERTY_NAME;
        $extended_property_names[] = self::PROPERTY_IDENTIFICATION_CODE;
        $extended_property_names[] = self::PROPERTY_EMAIL;
        $extended_property_names[] = self::PROPERTY_COMMUNICATION;
        $extended_property_names[] = self::PROPERTY_LANGUAGE;
        $extended_property_names[] = self::PROPERTY_PHOTO;
        
        return parent::get_default_property_names($extended_property_names);
    }

    /**
     *
     * @return DataManagerInterface
     */
    public function get_data_manager()
    {
        // return DataManager :: getInstance();
    }

    /**
     *
     * @return boolean
     */
    public function has_photo()
    {
        return $this->get_photo() instanceof Photo && $this->get_photo()->get_mime_type() &&
             $this->get_photo()->get_data();
    }
}
