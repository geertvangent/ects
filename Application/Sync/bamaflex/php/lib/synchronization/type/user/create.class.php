<?php
namespace application\ehb_sync\bamaflex;

/**
 * @package ehb.sync;
 */

use common\libraries\AndCondition;

use common\libraries\OrCondition;

use common\libraries\PatternMatchCondition;



use common\libraries\NotCondition;

use user\User;
use user\UserDataManager;

class CreateUserSynchronization extends UserSynchronization
{

    public function get_data()
    {
        $conditions = array();

        $conditions[] = new PatternMatchCondition(User :: PROPERTY_OFFICIAL_CODE, '*0*');
        $conditions[] = new PatternMatchCondition(User :: PROPERTY_OFFICIAL_CODE, '*1*');
        $conditions[] = new PatternMatchCondition(User :: PROPERTY_OFFICIAL_CODE, '*2*');
        $conditions[] = new PatternMatchCondition(User :: PROPERTY_OFFICIAL_CODE, '*3*');
        $conditions[] = new PatternMatchCondition(User :: PROPERTY_OFFICIAL_CODE, '*4*');
        $conditions[] = new PatternMatchCondition(User :: PROPERTY_OFFICIAL_CODE, '*5*');
        $conditions[] = new PatternMatchCondition(User :: PROPERTY_OFFICIAL_CODE, '*6*');
        $conditions[] = new PatternMatchCondition(User :: PROPERTY_OFFICIAL_CODE, '*7*');
        $conditions[] = new PatternMatchCondition(User :: PROPERTY_OFFICIAL_CODE, '*8*');
        $conditions[] = new PatternMatchCondition(User :: PROPERTY_OFFICIAL_CODE, '*9*');

        $condition = new OrCondition($conditions);

        $conditions = array();
        $conditions[] = $condition;
        $conditions[] = new NotCondition(new PatternMatchCondition(User :: PROPERTY_OFFICIAL_CODE, 'EXT*'));

        $condition = new AndCondition($conditions);
        $official_codes = UserDataManager :: get_instance()->retrieve_distinct(User :: get_table_name(), User :: PROPERTY_OFFICIAL_CODE, $condition);
        sort($official_codes, SORT_NUMERIC);

        if (count($official_codes) == 0)
        {
            $official_code = 0;
        }
        else
        {
            $official_code = array_pop($official_codes);
        }

        $query = 'SELECT * FROM [dbo].[v_sync_user] WHERE id > "' . $official_code . '"';

        return $this->get_result($query);
    }

    public function get_type()
    {
        return 'create';
    }

    public function process_data($person)
    {
        $utf_last_name = $this->convert_to_utf8($person[self :: RESULT_PROPERTY_LAST_NAME]);
        $utf_first_name = $this->convert_to_utf8($person[self :: RESULT_PROPERTY_FIRST_NAME]);

        $user = new User();

        $user->set_lastname($utf_last_name);
        $user->set_firstname($utf_first_name);
        $user->set_disk_quota($person[self :: RESULT_PROPERTY_QUOTA]*1024*1024);

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

        $user->set_auth_source('cas');
        $user->set_password('PLACEHOLDER');
        $user->set_expiration_date(0);
        $user->set_activation_date(0);
        $user->set_database_quota(10000);
        $user->set_platformadmin(0);

        if ($user->create())
        {
            echo 'Added: [' . $person[self :: RESULT_PROPERTY_PERSON_ID] . ']' . $utf_first_name . ' ' . $utf_last_name . "\n";

        }
        else
        {
            echo '++ Failed: ' . $utf_first_name . ' ' . $utf_last_name . "\n";
        }

        unset($user);
        flush();
    }

    public function run()
    {
        $user_result_set = $this->get_data();

        while ($user = $user_result_set->next_result(false))
        {
            $this->process_data($user);
        }
    }
}
