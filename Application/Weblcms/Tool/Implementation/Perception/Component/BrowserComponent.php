<?php
namespace Ehb\Application\Weblcms\Tool\Implementation\Perception\Component;

use Chamilo\Libraries\Format\Structure\ActionBar\Button;
use Chamilo\Libraries\Format\Structure\ActionBar\ButtonGroup;
use Chamilo\Libraries\Format\Structure\ActionBar\ButtonToolBar;
use Chamilo\Libraries\Format\Structure\ActionBar\Renderer\ButtonToolBarRenderer;
use Chamilo\Libraries\Format\Table\Interfaces\TableSupport;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrieveParameters;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\Condition\InCondition;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Ehb\Application\Weblcms\Tool\Implementation\Perception\Manager;
use Ehb\Application\Weblcms\Tool\Implementation\Perception\Storage\DataClass\Password;
use Ehb\Application\Weblcms\Tool\Implementation\Perception\Storage\DataManager;
use Ehb\Application\Weblcms\Tool\Implementation\Perception\Table\Password\PasswordTable;
use Chamilo\Configuration\Configuration;
use Chamilo\Libraries\Mail\ValueObject\Mail;
use Chamilo\Libraries\Mail\Mailer\MailerFactory;

/**
 *
 * @package Ehb\Application\Weblcms\Tool\Implementation\Perception\Component
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class BrowserComponent extends Manager implements TableSupport
{

    /**
     *
     * @var ButtonToolBarRenderer
     */
    private $buttonToolbarRenderer;

    public function run()
    {
        $this->buttonToolbarRenderer = $this->getButtonToolbarRenderer();
        
        if (! $this->get_course()->is_course_admin($this->get_user()))
        {
            $password = DataManager::retrieve(
                Password::class_name(), 
                new DataClassRetrieveParameters(
                    new EqualityCondition(
                        new PropertyConditionVariable(Password::class_name(), Password::PROPERTY_USER_ID), 
                        new StaticConditionVariable($this->get_user_id()))));
            
            if ($password instanceof Password)
            {
                $this->send_mail($password);
                
                $this->redirect(
                    Translation::get('PasswordMailed'), 
                    false, 
                    array(\Chamilo\Application\Weblcms\Manager::PARAM_TOOL => null, self::PARAM_ACTION => null));
            }
            else
            {
                $this->redirect(
                    Translation::get('NoPerceptionPassword'), 
                    true, 
                    array(\Chamilo\Application\Weblcms\Manager::PARAM_TOOL => null, self::PARAM_ACTION => null));
            }
        }
        else
        {
            
            $table = new PasswordTable($this);
            
            $html = array();
            
            $html[] = $this->render_header();
            $html[] = $this->buttonToolbarRenderer->render();
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
        $user_ids = DataManager::get_course_user_ids($this->get_course_id());
        
        if (count($user_ids) == 0)
        {
            return new EqualityCondition(
                new PropertyConditionVariable(Password::class_name(), Password::PROPERTY_USER_ID), 
                new StaticConditionVariable(- 1));
        }
        
        return new InCondition(
            new PropertyConditionVariable(Password::class_name(), Password::PROPERTY_USER_ID), 
            $user_ids);
    }

    public function getButtonToolbarRenderer()
    {
        if (! isset($this->buttonToolbarRenderer))
        {
            $buttonToolbar = new ButtonToolBar();
            $commonActions = new ButtonGroup();
            
            $commonActions->addButton(
                new Button(
                    Translation::get('GeneratePasswords'), 
                    Theme::getInstance()->getImagePath(self::package(), 'Action/' . self::ACTION_GENERATE), 
                    $this->get_url(array(self::PARAM_ACTION => self::ACTION_GENERATE))));
            
            $commonActions->addButton(
                new Button(
                    Translation::get('MailPasswords'), 
                    Theme::getInstance()->getImagePath(self::package(), 'Action/' . self::ACTION_MAIL), 
                    $this->get_url(array(self::PARAM_ACTION => self::ACTION_MAIL))));
            
            $commonActions->addButton(
                new Button(
                    Translation::get('ExportPasswords'), 
                    Theme::getInstance()->getImagePath(self::package(), 'Action/' . self::ACTION_EXPORT), 
                    $this->get_url(array(self::PARAM_ACTION => self::ACTION_EXPORT))));
            
            $buttonToolbar->addButtonGroup($commonActions);
            
            $this->buttonToolbarRenderer = new ButtonToolBarRenderer($buttonToolbar);
        }
        
        return $this->buttonToolbarRenderer;
    }

    public function send_mail($password)
    {
        set_time_limit(3600);
        
        $recipient = $password->get_user();
        
        $siteName = Configuration::getInstance()->get_setting(array('Chamilo\Core\Admin', 'site_name'));
        
        $subject = Translation::get('PasswordMailTitle', array('PLATFORM' => $siteName));
        
        $body = Translation::get(
            'PasswordMailBody', 
            array(
                'USER' => $recipient->get_fullname(), 
                'EMAIL' => $recipient->get_email(), 
                'PLATFORM' => $siteName, 
                'SENDER' => $this->get_user()->get_fullname(), 
                'PASSWORD' => $password->get_password()));
        
        $mail = new Mail(
            $subject, 
            $body, 
            array($recipient->get_email()), 
            true, 
            array(), 
            array(), 
            $this->get_user()->get_fullname(), 
            $this->get_user()->get_email());
        
        $mailerFactory = new MailerFactory(Configuration::getInstance());
        $mailer = $mailerFactory->getActiveMailer();
        
        $mailer->sendMail($mail);
        
        return true;
    }
}
