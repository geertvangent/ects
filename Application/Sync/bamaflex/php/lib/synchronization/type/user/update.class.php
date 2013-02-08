<?php
namespace application\ehb_sync\bamaflex;

/**
 * @package ehb.sync;
 */


use user\UserDataManager;
use user\User;

class UpdateUserSynchronization extends UserSynchronization
{

    function get_data()
    {
        $query = 'SELECT * FROM [INFORDATSYNC].[dbo].[v_sync_user] WHERE status not like 0';
        return $this->get_result($query);
    }

    function get_type()
    {
        return 'update';
    }

    function process_data($person)
    {
        $user = UserDataManager :: get_instance()->retrieve_user_by_official_code($person[self :: RESULT_PROPERTY_PERSON_ID]);

        $utf_last_name = $this->convert_to_utf8($person[self :: RESULT_PROPERTY_LAST_NAME]);
        $utf_first_name = $this->convert_to_utf8($person[self :: RESULT_PROPERTY_FIRST_NAME]);

        if ($user instanceof User)
        {
            $user_copy = clone $user;

            $user->set_lastname($utf_last_name);
            $user->set_firstname($utf_first_name);

            if (($person[self :: RESULT_PROPERTY_QUOTA] * 1024 * 1024) > $user->get_disk_quota())
            {
                $user->set_disk_quota($person[self :: RESULT_PROPERTY_QUOTA] * 1024 * 1024);
            }

            switch ($person[self :: RESULT_PROPERTY_STATUS])
            {
                case 0 :
                    $user->set_active(0);
                    $user->set_status(5);
                    $user->set_email($person[self :: RESULT_PROPERTY_PERSON_ID] . '@archive.ehb.be');
                    $user->set_username($person[self :: RESULT_PROPERTY_PERSON_ID]);
                    break;
                case 1 :
                    $user->set_active(1);
                    $user->set_status(1);

                    if ($person[self :: RESULT_PROPERTY_EMAIL_EMPLOYEE])
                    {
                        $user->set_username($this->convert_to_utf8($person[self :: RESULT_PROPERTY_EMAIL_EMPLOYEE]));
                        $user->set_email($this->convert_to_utf8($person[self :: RESULT_PROPERTY_EMAIL_EMPLOYEE]));
                    }
                    elseif ($person[self :: RESULT_PROPERTY_EMAIL_STUDENT])
                    {
                        $user->set_username($this->convert_to_utf8($person[self :: RESULT_PROPERTY_EMAIL_STUDENT]));
                        $user->set_email($this->convert_to_utf8($person[self :: RESULT_PROPERTY_EMAIL_STUDENT]));
                    }
                    else
                    {
                        $user->set_username($person[self :: RESULT_PROPERTY_PERSON_ID]);
                        $user->set_email($person[self :: RESULT_PROPERTY_PERSON_ID] . '@void.ehb.be');
                    }
                    break;
                case 2 :
                    $user->set_active(1);
                    $user->set_status(1);

                    if ($person[self :: RESULT_PROPERTY_EMAIL_EMPLOYEE])
                    {
                        $user->set_username($this->convert_to_utf8($person[self :: RESULT_PROPERTY_EMAIL_EMPLOYEE]));
                        $user->set_email($this->convert_to_utf8($person[self :: RESULT_PROPERTY_EMAIL_EMPLOYEE]));
                    }
                    else
                    {
                        $user->set_username($person[self :: RESULT_PROPERTY_PERSON_ID]);
                        $user->set_email($person[self :: RESULT_PROPERTY_PERSON_ID] . '@void.ehb.be');
                    }
                    break;
                case 3 :
                    $user->set_active(1);
                    $user->set_status(5);

                    if ($person[self :: RESULT_PROPERTY_EMAIL_STUDENT])
                    {
                        $user->set_username($this->convert_to_utf8($person[self :: RESULT_PROPERTY_EMAIL_STUDENT]));
                        $user->set_email($this->convert_to_utf8($person[self :: RESULT_PROPERTY_EMAIL_STUDENT]));
                    }
                    else
                    {
                        $user->set_username($person[self :: RESULT_PROPERTY_PERSON_ID]);
                        $user->set_email($person[self :: RESULT_PROPERTY_PERSON_ID] . '@void.ehb.be');
                    }
                    break;
            }

            $user->set_official_code($person[self :: RESULT_PROPERTY_PERSON_ID]);

            //            $user->set_auth_source('cas');
            //            $user->set_password('PLACEHOLDER');
            //            $user->set_expiration_date(0);
            //            $user->set_activation_date(0);
            //            $user->set_version_quota(20);
            //            $user->set_database_quota(300);
            //            $user->set_disk_quota(209715200);
            //            $user->set_platformadmin(0);


            if ($user != $user_copy)
            {
                if ($user->update())
                {
                    echo 'Updated:  [' . $person[self :: RESULT_PROPERTY_PERSON_ID] . ']' . $utf_first_name . ' ' . $utf_last_name . "\n";

                }
                else
                {
                    echo '++ Failed:' . $utf_first_name . ' ' . $utf_last_name . "\n";
                }
            }

            unset($user);
            unset($user_copy);
        }
        else
        {
            echo '++ Failed:' . $utf_first_name . ' ' . $utf_last_name . "\n";
        }

        flush();
    }

    function run()
    {
        $user_result_set = $this->get_data();

        while ($user = $user_result_set->next_result(false))
        {
            $this->process_data($user);
        }
    }
}
