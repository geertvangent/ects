<?php
namespace Application\EhbSync\cas\synchronization\type\user;

/**
 *
 * @package ehb.sync;
 */
use core\user\User;

class AllUserSynchronization extends UserSynchronization
{

    public function get_data()
    {
        return \application\cas_user\account\DataManager :: retrieves(
            \application\cas_user\account\Account :: class_name());
    }

    public function get_type()
    {
        return 'create';
    }

    public function process_data($person)
    {
        $user = \core\user\DataManager :: retrieve_user_by_official_code($person->get_person_id());
        
        if (! $user instanceof User)
        {
            $user = new User();
            
            $user->set_official_code($person->get_person_id());
            
            $user->set_auth_source('cas');
            $user->set_password('PLACEHOLDER');
            $user->set_expiration_date(0);
            $user->set_activation_date(0);
            $user->set_database_quota(10000);
            $user->set_platformadmin(0);
        }
        else
        {
            $user_copy = clone $user;
        }
        
        $user->set_lastname($person->get_last_name());
        $user->set_firstname($person->get_first_name());
        
        $quota = 40;
        if ($quota > $user->get_disk_quota())
        {
            $user->set_disk_quota($quota * 1024 * 1024);
        }
        
        switch ($person->get_status())
        {
            case 0 :
                $user->set_active(0);
                $user->set_status(5);
                $user->set_email($person->get_person_id() . '@archive.ehb.be');
                $user->set_username($person->get_person_id());
                $user->set_disk_quota(0);
                break;
            case 1 :
                $user->set_active(1);
                $user->set_status(1);
                
                $user->set_username($person->get_email());
                $user->set_email($person->get_email());
                
                break;
            case 5 :
                $user->set_active(1);
                $user->set_status(5);
                
                $user->set_username($person->get_email());
                $user->set_email($person->get_email());
                break;
        }
        
        if ($user_copy instanceof User)
        {
            if ($user != $user_copy)
            {
                if ($user->update())
                {
                    echo 'Updated:  [' . $person->get_person_id() . ']' . $person->get_first_name() . ' ' .
                         $person->get_last_name() . "\n";
                }
                else
                {
                    echo '++ Failed:' . $person->get_first_name() . ' ' . $person->get_last_name() . "\n";
                }
            }
            
            unset($user);
            unset($user_copy);
        }
        else
        {
            
            if ($user->create())
            {
                echo 'Added: [' . $person->get_person_id() . ']' . $person->get_first_name() . ' ' .
                     $person->get_last_name() . "\n";
            }
            else
            {
                echo '++ Failed: ' . $person->get_first_name() . ' ' . $person->get_last_name() . "\n";
            }
            
            unset($user);
        }
        flush();
    }

    public function run()
    {
        $user_result_set = $this->get_data();
        
        while ($user = $user_result_set->next_result())
        {
            $this->process_data($user);
        }
    }
}
