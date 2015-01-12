<?php
namespace Chamilo\Application\Discovery\Module\Profile\Implementation\Chamilo\DataManager;

use Chamilo\Core\User\Storage\DataClass\UserSetting;
use Chamilo\Core\User\Storage\DataClass\User;
use Chamilo\Libraries\Platform\PlatformSetting;
use Chamilo\Application\Discovery\Module\Profile\Photo;
use Chamilo\Application\Discovery\Module\Profile\Communication;
use Chamilo\Application\Discovery\Module\Profile\Email;
use Chamilo\Application\Discovery\Module\Profile\IdentificationCode;
use Chamilo\Application\Discovery\Module\Profile\Name;
use Chamilo\Libraries\File\FileType;

class DataSource
{

    /**
     *
     * @param $id int
     * @return \application\discovery\module\profile\implementation\chamilo\Profile boolean
     */
    public function retrieve_profile($parameters)
    {
        $user = \Chamilo\Core\User\Storage\DataManager :: get_instance()->retrieve_user($parameters->get_user_id());
        if ($user instanceof User)
        {
            $name = new Name();
            $name->set_first_name($user->get_firstname());
            $name->set_last_name($user->get_lastname());

            $company_id = new IdentificationCode();
            $company_id->set_type(IdentificationCode :: TYPE_COMPANY);
            $company_id->set_code($user->get_official_code());

            $profile = new Profile();
            $profile->set_title($user->get_fullname());
            $profile->set_name($name);
            $profile->add_identification_code($company_id);
            $email = new Email();
            $email->set_address($user->get_email());
            $email->set_type(Email :: TYPE_OFFICIAL);
            $profile->add_email($email);

            $communication = new Communication();
            $communication->set_number($user->get_phone());
            $communication->set_type(Communication :: TYPE_DOMICILE);
            $communication->set_device(Communication :: DEVICE_TELEPHONE);
            $profile->add_communication($communication);

            $profile->set_language($this->get_language());
            $profile->set_photo($this->retrieve_photo($user));

            $profile->set_username($user->get_username());
            $profile->set_timezone($this->get_timezone());

            return $profile;
        }
        else
        {
            return false;
        }
    }

    /**
     *
     * @param $id int
     * @return string
     */
    public function get_language($id)
    {
        $user_language_is_allowed = PlatformSetting :: get(
            'allow_user_change_platform_language',
            \Chamilo\Core\User\Manager :: context());

        if ($user_language_is_allowed)
        {
            $setting = \Chamilo\Configuration\DataManager :: retrieve_setting_from_variable_name('platform_language');
            $user_setting = \Chamilo\Core\User\Storage\DataManager :: get_instance()->retrieve_user_setting($id, $setting->get_id());

            if ($user_setting instanceof UserSetting)
            {
                $language_code = $user_setting->get_value();
            }
            else
            {
                $language_code = $setting->get_value();
            }
        }
        else
        {
            $language_code = PlatformSetting :: get('platform_language');
        }

        return \Chamilo\Configuration\DataManager :: retrieve_language_from_isocode($language_code)->get_english_name();
    }

    /**
     *
     * @param $id int
     * @return string
     */
    public function get_timezone($id)
    {
        $user_timezone_is_allowed = PlatformSetting :: get(
            'allow_user_change_platform_timezone',
            \Chamilo\Core\User\Manager :: context());

        if ($user_timezone_is_allowed)
        {
            $setting = \Chamilo\Configuration\DataManager :: retrieve_setting_from_variable_name('platform_timezone');
            $user_setting = \Chamilo\Core\User\Storage\DataManager :: get_instance()->retrieve_user_setting($id, $setting->get_id());

            if ($user_setting instanceof UserSetting)
            {
                return $user_setting->get_value();
            }
            else
            {
                return $setting->get_value();
            }
        }
        else
        {
            return PlatformSetting :: get('platform_timezone');
        }
    }

    /**
     *
     * @param $user User
     * @return \application\discovery\module\profile\Photo
     */
    public function retrieve_photo(User $user)
    {
        $photo_path = $user->get_full_picture_path();

        $photo_extension = pathinfo($photo_path, PATHINFO_EXTENSION);
        $photo_data = file_get_contents($photo_path);

        $photo = new Photo();
        $photo->set_mime_type(FileType :: get_mimetype($photo_extension));
        $photo->set_data(base64_encode($photo_data));

        return $photo;
    }
}
