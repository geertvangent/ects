<?php
namespace application\ehb_sync\bamaflex;

use user\User;
use user\UserDataManager;

use common\libraries\Filesystem;
use common\libraries\Utilities;

/**
 * @package ehb.sync;
 */

class UserSynchronization extends Synchronization
{
    const RESULT_PROPERTY_PERSON_ID = 'id';
    const RESULT_PROPERTY_ACADEMIC_YEAR = 'schooljaar';
    const RESULT_PROPERTY_EMAIL_EMPLOYEE = 'email_employee';
    const RESULT_PROPERTY_EMAIL_STUDENT = 'email_student';
    const RESULT_PROPERTY_FIRST_NAME = 'first_name';
    const RESULT_PROPERTY_LAST_NAME = 'last_name';
    const RESULT_PROPERTY_STATUS = 'status';

    function run()
    {
        foreach (self :: get_user_types() as $type)
        {
            $synchronization = self :: factory($type);
            $synchronization->run();
        }
    }

    static function get_user_types()
    {
        $types = array();
        $types[] = 'create';
        $types[] = 'update';
        
        return $types;
    }

    static function factory($type)
    {
        $file = dirname(__FILE__) . '/user/' . $type . '.class.php';
        $class = __NAMESPACE__ . '\\' . Utilities :: underscores_to_camelcase($type) . 'UserSynchronization';
        if (file_exists($file))
        {
            require_once $file;
            return new $class();
        }
    }

    //     function process_data($person)
    //     {
    //         $user = UserDataManager :: get_instance()->retrieve_user_by_official_code($person[self :: RESULT_PROPERTY_PERSON_ID]);
    

    //         $user_exists = $user instanceof User;
    

    //         $utf_last_name = $this->convert_to_utf8($person[self :: RESULT_PROPERTY_LAST_NAME]);
    //         $utf_first_name = $this->convert_to_utf8($person[self :: RESULT_PROPERTY_FIRST_NAME]);
    

    //         if (! $user_exists)
    //         {
    //             $user = new User();
    //             $user->set_lastname($utf_last_name);
    //             $user->set_firstname($utf_first_name);
    //             $user->set_active(1);
    //             $user->set_status(1);
    //             $user->set_username($person[self :: RESULT_PROPERTY_PERSON_ID]);
    //             $user->set_email($person[self :: RESULT_PROPERTY_PERSON_ID] . '@void.ehb.be');
    //             $user->set_official_code($person[self :: RESULT_PROPERTY_PERSON_ID]);
    //             $user->set_auth_source('cas');
    //             $user->set_password('PLACEHOLDER');
    //             $user->set_expiration_date(0);
    //             $user->set_activation_date(0);
    //             $user->set_version_quota(20);
    //             $user->set_database_quota(300);
    //             $user->set_disk_quota(209715200);
    //             $user->set_platformadmin(0);
    

    //             if ($user->create())
    //             {
    //                 echo 'Added: ' . $utf_first_name . ' ' . $utf_last_name . "\n";
    //             }
    //         }
    //         flush();
    //     }  
    

    function get_type()
    {
        return 'person';
    }
}
?>