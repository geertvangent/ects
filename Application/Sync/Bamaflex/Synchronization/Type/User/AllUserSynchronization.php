<?php
namespace Ehb\Application\Sync\Bamaflex\Synchronization\Type\User;

/**
 *
 * @package ehb.sync;
 */
use Chamilo\Core\User\Storage\DataClass\User;
use Ehb\Application\Sync\Bamaflex\Synchronization\Type\UserSynchronization;
use Chamilo\Core\User\Storage\DataClass\UserSetting;
use Chamilo\Configuration\Storage\DataClass\Setting;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrieveParameters;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Chamilo\Libraries\Storage\Query\Condition\AndCondition;

class AllUserSynchronization extends UserSynchronization
{

    private $languageSetting;

    public function get_data()
    {
        $academic_year = $this->get_academic_year();
        $academic_year = explode(',', $academic_year);

        $query = 'EXEC [dbo].[sp_sync_user] @academiejaar = N\'' . $academic_year[0] . '\'';

        return $this->get_result($query);
    }

    public function get_type()
    {
        return 'create';
    }

    /**
     *
     * @return \Chamilo\Libraries\Storage\DataClass\DataClass
     */
    public function getLanguageSetting()
    {
        if (! isset($this->languageSetting))
        {
            $conditions = array();
            $conditions[] = new EqualityCondition(
                new PropertyConditionVariable(Setting :: class_name(), Setting :: PROPERTY_CONTEXT),
                new StaticConditionVariable('Chamilo\Core\Admin'));
            $conditions[] = new EqualityCondition(
                new PropertyConditionVariable(Setting :: class_name(), Setting :: PROPERTY_VARIABLE),
                new StaticConditionVariable('platform_language'));
            $condition = new AndCondition($conditions);

            $this->languageSetting = \Chamilo\Libraries\Storage\DataManager\DataManager :: retrieve(
                Setting :: class_name(),
                new DataClassRetrieveParameters($condition));
        }

        return $this->languageSetting;
    }

    public function process_data($person)
    {
        $user = \Chamilo\Core\User\Storage\DataManager :: retrieve_user_by_official_code(
            $person[self :: RESULT_PROPERTY_PERSON_ID]);

        $utf_last_name = trim($person[self :: RESULT_PROPERTY_LAST_NAME]);
        $utf_first_name = trim($person[self :: RESULT_PROPERTY_FIRST_NAME]);
        if (! $user instanceof User)
        {
            $user = new User();

            $user->set_official_code($person[self :: RESULT_PROPERTY_PERSON_ID]);

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
                $user->set_disk_quota(0);
                break;
            case 1 :
                $user->set_active(1);

                if ($person[self :: RESULT_PROPERTY_EMAIL_EMPLOYEE])
                {
                    $user->set_username($this->convert_to_utf8($person[self :: RESULT_PROPERTY_EMAIL_EMPLOYEE]));
                    $user->set_email($this->convert_to_utf8($person[self :: RESULT_PROPERTY_EMAIL_EMPLOYEE]));
                    $user->set_status(1);
                }
                elseif ($person[self :: RESULT_PROPERTY_EMAIL_STUDENT])
                {
                    $user->set_username($this->convert_to_utf8($person[self :: RESULT_PROPERTY_EMAIL_STUDENT]));
                    $user->set_email($this->convert_to_utf8($person[self :: RESULT_PROPERTY_EMAIL_STUDENT]));
                    $user->set_status(5);
                }
                else
                {
                    $user->set_username($person[self :: RESULT_PROPERTY_PERSON_ID]);
                    $user->set_email($person[self :: RESULT_PROPERTY_PERSON_ID] . '@void.ehb.be');
                    $user->set_status(5);
                }
                break;
            case 2 :
                $user->set_active(1);

                if ($person[self :: RESULT_PROPERTY_EMAIL_EMPLOYEE])
                {
                    $user->set_username($this->convert_to_utf8($person[self :: RESULT_PROPERTY_EMAIL_EMPLOYEE]));
                    $user->set_email($this->convert_to_utf8($person[self :: RESULT_PROPERTY_EMAIL_EMPLOYEE]));
                    $user->set_status(1);
                }
                else
                {
                    $user->set_username($person[self :: RESULT_PROPERTY_PERSON_ID]);
                    $user->set_email($person[self :: RESULT_PROPERTY_PERSON_ID] . '@void.ehb.be');
                    $user->set_status(5);
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

        if ($user_copy instanceof User)
        {
            if ($user != $user_copy)
            {
                try
                {
                    if ($user->update())
                    {
                        echo 'Updated:  [' . $person[self :: RESULT_PROPERTY_PERSON_ID] . ']' . $utf_first_name . ' ' .
                             $utf_last_name . "\n";
                    }
                    else
                    {
                        echo '++ FAIL:  [' . $person[self :: RESULT_PROPERTY_PERSON_ID] . ']' . $utf_first_name . ' ' .
                             $utf_last_name . "\n";
                    }
                }
                catch (\Exception $exception)
                {
                    echo '++ FAIL:  [' . $person[self :: RESULT_PROPERTY_PERSON_ID] . ']' . $utf_first_name . ' ' .
                         $utf_last_name . "\n";
                }
            }

            unset($user);
            unset($user_copy);
        }
        else
        {
            try
            {
                if ($user->create())
                {
                    // Language 5 = Dutch
                    if ($person[self :: RESULT_PROPERTY_LANGUAGE] != 5)
                    {
                        $user_setting = new UserSetting();
                        $user_setting->set_user_id($user->get_id());
                        $user_setting->set_setting_id($this->getLanguageSetting()->getId());
                        $user_setting->set_value('en');
                        $user_setting->create();
                    }

                    echo 'Added: [' . $person[self :: RESULT_PROPERTY_PERSON_ID] . ']' . $utf_first_name . ' ' .
                         $utf_last_name . "\n";
                }
                else
                {
                    echo '++ FAIL:  [' . $person[self :: RESULT_PROPERTY_PERSON_ID] . ']' . $utf_first_name . ' ' .
                         $utf_last_name . "\n";
                }
            }
            catch (\Exception $exception)
            {
                echo '++ FAIL:  [' . $person[self :: RESULT_PROPERTY_PERSON_ID] . ']' . $utf_first_name . ' ' .
                     $utf_last_name . "\n";
            }

            unset($user);
        }
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
