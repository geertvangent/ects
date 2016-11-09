<?php
namespace Ehb\Application\Weblcms\Tool\Implementation\Perception\Component;

use Chamilo\Libraries\Mail\Mail;
use Chamilo\Libraries\Platform\Configuration\PlatformSetting;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;
use Chamilo\Libraries\Storage\Query\Condition\InCondition;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Ehb\Application\Weblcms\Tool\Implementation\Perception\Manager;
use Ehb\Application\Weblcms\Tool\Implementation\Perception\Storage\DataClass\Password;
use Ehb\Application\Weblcms\Tool\Implementation\Perception\Storage\DataManager;

/**
 *
 * @package Ehb\Application\Weblcms\Tool\Implementation\Perception\Component
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class MailerComponent extends Manager
{

    public function run()
    {
        // Just in case new users were added ...
        $this->generate_passwords();

        $users_ids = DataManager::get_course_user_ids($this->get_course_id());
        $passwords = DataManager::retrieves(
            Password::class_name(),
            new DataClassRetrievesParameters(
                new InCondition(
                    new PropertyConditionVariable(Password::class_name(), Password::PROPERTY_USER_ID),
                    $users_ids)));

        $failures = 0;

        while ($password = $passwords->next_result())
        {
            if (! $this->send_mail($password))
            {
                $failures ++;
            }
        }

        if ($failures)
        {
            if ($passwords->size() == 1)
            {
                $message = 'PasswordNotMailed';
            }
            elseif ($passwords->size() > $failures)
            {
                $message = 'SomePasswordsNotMailed';
            }
            else
            {
                $message = 'PasswordsNotMailed';
            }
        }
        else
        {
            if ($passwords->size() == 1)
            {
                $message = 'PasswordMailed';
            }
            else
            {
                $message = 'PasswordsMailed';
            }
        }

        $this->redirect(
            Translation::get($message),
            ($failures ? true : false),
            array(self::PARAM_ACTION => self::ACTION_BROWSE));
    }

    public function send_mail($password)
    {
        set_time_limit(3600);

        $recipient = $password->get_user();

        $siteName = Configuration::getInstance()->get_setting(array('Chamilo\Core\Admin', 'site_name'));

        $title = Translation::get('PasswordMailTitle', array('PLATFORM' => $siteName));

        $body = Translation::get(
            'PasswordMailBody',
            array(
                'USER' => $recipient->get_fullname(),
                'EMAIL' => $recipient->get_email(),
                'PLATFORM' => $siteName,
                'SENDER' => $this->get_user()->get_fullname(),
                'PASSWORD' => $password->get_password()));

        $mail = Mail::factory(
            $title,
            $body,
            array($recipient->get_email()),
            array(Mail::NAME => $this->get_user()->get_fullname(), Mail::EMAIL => $this->get_user()->get_email()));

        $mail->send();

        return true;
    }

    public function generate_passwords()
    {
        $result = DataManager::generate_passwords($this->get_course_id());

        if ($result[DataManager::GENERATION_FAILURES] > 0)
        {
            if ($result[DataManager::GENERATION_ATTEMPTS] == 1)
            {
                $message = 'PasswordNotGenerated';
            }
            elseif ($result[DataManager::GENERATION_ATTEMPTS] > $result[DataManager::GENERATION_FAILURES])
            {
                $message = 'SomePasswordsNotGenerated';
            }
            else
            {
                $message = 'PasswordsNotGenerated';
            }

            $this->redirect(
                Translation::get($message),
                (($result[DataManager::GENERATION_FAILURES] > 0) ? true : false),
                array(self::PARAM_ACTION => self::ACTION_BROWSE));
            exit();
        }
    }
}
