<?php
namespace Ehb\Application\Weblcms\Tool\Implementation\Perception\Component;

use Ehb\Application\Weblcms\Tool\Implementation\Perception\Manager;
use Ehb\Application\Weblcms\Tool\Implementation\Perception\Storage\DataManager;
use Chamilo\Libraries\Storage\Query\Condition\InCondition;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrieveParameters;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Format\Structure\ActionBarRenderer;
use Chamilo\Libraries\Format\Structure\ToolbarItem;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Configuration\PlatformSetting;
use Chamilo\Libraries\Mail\Mail;
use Chamilo\Libraries\Format\Table\Interfaces\TableSupport;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Ehb\Application\Weblcms\Tool\Implementation\Perception\Table\Password\PasswordTable;
use Ehb\Application\Weblcms\Tool\Implementation\Perception\Storage\DataClass\Password;

/**
 *
 * @package Ehb\Application\Weblcms\Tool\Implementation\Perception\Component
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class BrowserComponent extends Manager implements TableSupport
{

    public function run()
    {
        if (! $this->get_course()->is_course_admin($this->get_user()))
        {
            $password = DataManager :: retrieve(
                Password :: class_name(),
                new DataClassRetrieveParameters(
                    new EqualityCondition(
                        new PropertyConditionVariable(Password :: class_name(), Password :: PROPERTY_USER_ID),
                        new StaticConditionVariable($this->get_user_id()))));

            if ($password instanceof Password)
            {
                $this->send_mail($password);

                $this->redirect(
                    Translation :: get('PasswordMailed'),
                    false,
                    array(\Chamilo\Application\Weblcms\Manager :: PARAM_TOOL => null, self :: PARAM_ACTION => null));
            }
            else
            {
                $this->redirect(
                    Translation :: get('NoPerceptionPassword'),
                    true,
                    array(\Chamilo\Application\Weblcms\Manager :: PARAM_TOOL => null, self :: PARAM_ACTION => null));
            }
        }
        else
        {

            $table = new PasswordTable($this);

            $html = array();

            $html[] = $this->render_header();
            $html[] = $this->get_action_bar()->as_html();
            $html[] = $table->as_html();
            $html[] = $this->render_footer();

            return implode(PHP_EOL, $html);
        }
    }

    /*
     * (non-PHPdoc) @see \common\libraries\NewObjectTableSupport::get_object_table_condition()
     */
    public function get_table_condition($object_table_class_name)
    {
        $user_ids = DataManager :: get_course_user_ids($this->get_course_id());

        if (count($user_ids) == 0)
        {
            return new EqualityCondition(
                new PropertyConditionVariable(Password :: class_name(), Password :: PROPERTY_USER_ID),
                new StaticConditionVariable(- 1));
        }

        return new InCondition(
            new PropertyConditionVariable(Password :: class_name(), Password :: PROPERTY_USER_ID),
            $user_ids);
    }

    public function get_action_bar()
    {
        $action_bar = new ActionBarRenderer(ActionBarRenderer :: TYPE_HORIZONTAL);

        $action_bar->add_common_action(
            new ToolbarItem(
                Translation :: get('GeneratePasswords'),
                Theme :: getInstance()->getImagePath(self :: package(), 'Action/' . self :: ACTION_GENERATE),
                $this->get_url(array(self :: PARAM_ACTION => self :: ACTION_GENERATE))));

        $action_bar->add_common_action(
            new ToolbarItem(
                Translation :: get('MailPasswords'),
                Theme :: getInstance()->getImagePath(self :: package(), 'Action/' . self :: ACTION_MAIL),
                $this->get_url(array(self :: PARAM_ACTION => self :: ACTION_MAIL))));

        $action_bar->add_common_action(
            new ToolbarItem(
                Translation :: get('ExportPasswords'),
                Theme :: getInstance()->getImagePath(self :: package(), 'Action/' . self :: ACTION_EXPORT),
                $this->get_url(array(self :: PARAM_ACTION => self :: ACTION_EXPORT))));

        return $action_bar;
    }

    public function send_mail($password)
    {
        set_time_limit(3600);

        $recipient = $password->get_user();

        $title = Translation :: get('PasswordMailTitle', array('PLATFORM' => PlatformSetting :: get('site_name')));

        $body = Translation :: get(
            'PasswordMailBody',
            array(
                'USER' => $recipient->get_fullname(),
                'EMAIL' => $recipient->get_email(),
                'PLATFORM' => PlatformSetting :: get('site_name'),
                'SENDER' => PlatformSetting :: get('administrator_firstname') . ' ' .
                     PlatformSetting :: get('administrator_surname'),
                    'PASSWORD' => $password->get_password()));

        $mail = Mail :: factory(
            $title,
            $body,
            array($recipient->get_email()),
            array(
                Mail :: NAME => PlatformSetting :: get('administrator_firstname') . ' ' .
                     PlatformSetting :: get('administrator_surname'),
                    Mail :: EMAIL => PlatformSetting :: get('administrator_email')));

        $mail->send();

        return true;
    }
}
