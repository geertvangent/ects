<?php
namespace application\discovery\module\enrollment\implementation\chamilo;

use admin\AdminDataManager;
use admin\Setting;
use admin\AdminManager;

use user\UserSetting;
use user\UserManager;
use user\User;
use user\UserDataManager;

use common\libraries\MimeUtil;
use common\libraries\AndCondition;
use common\libraries\CoreApplication;
use common\libraries\PlatformSetting;
use common\libraries\EqualityCondition;

use application\discovery\module\enrollment\Photo;
use application\discovery\module\enrollment\Communication;
use application\discovery\module\enrollment\Email;
use application\discovery\module\enrollment\IdentificationCode;
use application\discovery\module\enrollment\Name;
use application\discovery\module\enrollment\DataManagerInterface;

use MDB2_Error;

class DataSource implements DataManagerInterface
{

    /**
     * @param int $id
     * @return \application\discovery\module\enrollment\implementation\chamilo\Enrollment|boolean
     */
    function retrieve_enrollment($id)
    {
        $user = UserDataManager :: get_instance()->retrieve_user($id);

        if ($user instanceof User)
        {
            $name = new Name();
            $name->set_first_name($user->get_firstname());
            $name->set_last_name($user->get_lastname());

            $company_id = new IdentificationCode();
            $company_id->set_type(IdentificationCode :: TYPE_COMPANY);
            $company_id->set_code($user->get_official_code());

            $enrollment = new Enrollment();
            $enrollment->set_title($user->get_fullname());
            $enrollment->set_name($name);
            $enrollment->add_identification_code($company_id);

            $email = new Email();
            $email->set_address($user->get_email());
            $email->set_type(Email :: TYPE_OFFICIAL);
            $enrollment->add_email($email);

            $communication = new Communication();
            $communication->set_number($user->get_phone());
            $communication->set_type(Communication :: TYPE_DOMICILE);
            $communication->set_device(Communication :: DEVICE_TELEPHONE);
            $enrollment->add_communication($communication);

            $enrollment->set_language($this->get_language($id));
            $enrollment->set_photo($this->retrieve_photo($user));

            $enrollment->set_username($user->get_username());
            $enrollment->set_timezone($this->get_timezone($id));

            return $enrollment;
        }
        else
        {
            return false;
        }
    }

    /**
     * @param int $id
     * @return string
     */
    function get_language($id)
    {
        $user_language_is_allowed = PlatformSetting :: get('allow_user_change_platform_language', CoreApplication :: get_application_namespace(UserManager :: APPLICATION_NAME));

        if ($user_language_is_allowed)
        {
            $setting = AdminDataManager :: get_instance()->retrieve_setting_from_variable_name('platform_language');
            $user_setting = UserDataManager :: get_instance()->retrieve_user_setting($id, $setting->get_id());

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
            $language_code = PlatformSetting :: get('platform_language', CoreApplication :: get_application_namespace(AdminManager :: APPLICATION_NAME));
        }

        return AdminDataManager :: get_instance()->retrieve_language_from_isocode($language_code)->get_english_name();
    }

    /**
     * @param int $id
     * @return string
     */
    function get_timezone($id)
    {
        $user_timezone_is_allowed = PlatformSetting :: get('allow_user_change_platform_timezone', CoreApplication :: get_application_namespace(UserManager :: APPLICATION_NAME));

        if ($user_timezone_is_allowed)
        {
            $setting = AdminDataManager :: get_instance()->retrieve_setting_from_variable_name('platform_timezone');
            $user_setting = UserDataManager :: get_instance()->retrieve_user_setting($id, $setting->get_id());

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
            return PlatformSetting :: get('platform_timezone', CoreApplication :: get_application_namespace(AdminManager :: APPLICATION_NAME));
        }
    }

    /**
     * @param User $user
     * @return \application\discovery\module\enrollment\Photo
     */
    function retrieve_photo(User $user)
    {
        $photo_path = $user->get_full_picture_path();
        $photo_extension = pathinfo($photo_path, PATHINFO_EXTENSION);
        $photo_data = file_get_contents($photo_path);

        $photo = new Photo();
        $photo->set_mime_type(MimeUtil :: ext_to_mimetype($photo_extension));
        $photo->set_data(base64_encode($photo_data));

        return $photo;
    }
}
?>