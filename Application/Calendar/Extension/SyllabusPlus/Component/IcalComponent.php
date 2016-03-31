<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Component;

use Chamilo\Configuration\Configuration;
use Chamilo\Core\User\Storage\DataClass\User;
use Chamilo\Libraries\Architecture\Application\Application;
use Chamilo\Libraries\Architecture\Interfaces\NoAuthenticationSupport;
use Chamilo\Libraries\Authentication\AuthenticationValidator;
use Chamilo\Libraries\Authentication\QueryAuthentication;
use Chamilo\Libraries\Calendar\Renderer\Type\ICalRenderer;
use Chamilo\Libraries\File\Redirect;
use Chamilo\Libraries\Format\Display;
use Chamilo\Libraries\Platform\Translation;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Manager;
use Ehb\Application\Calendar\Extension\SyllabusPlus\Service\CalendarRendererProvider;

/**
 *
 * @package Ehb\Application\Calendar\Extension\SyllabusPlus\Component
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class IcalComponent extends Manager implements NoAuthenticationSupport
{

    /**
     *
     * @var \Chamilo\Application\Calendar\Service\CalendarRendererProvider
     */
    private $calendarRendererProvider;

    public function run()
    {
        $authenticationValidator = new AuthenticationValidator($this->getRequest(), Configuration :: get_instance());

        if (! $authenticationValidator->isAuthenticated())
        {
            $authentication = QueryAuthentication :: factory('SecurityToken', $this->getRequest());
            $user = $authentication->login();

            if ($user instanceof User)
            {
                $this->getApplicationConfiguration()->setUser($user);
                $this->renderCalendar();
                $authentication->logout($user);
            }
            else
            {
                $response = new \Symfony\Component\HttpFoundation\Response();
                $response->setStatusCode(401);
                $response->send();
            }
        }
        else
        {
            if ($this->getRequest()->query->get(self :: PARAM_DOWNLOAD))
            {
                $this->renderCalendar();
            }
            else
            {
                $icalDownloadUrl = new Redirect(
                    array(
                        Application :: PARAM_CONTEXT => self :: package(),
                        self :: PARAM_ACTION => Manager :: ACTION_ICAL,
                        self :: PARAM_USER_USER_ID => $this->getUserCalendar()->getId(),
                        self :: PARAM_DOWNLOAD => 1));

                $icalExternalUrl = new Redirect(
                    array(
                        Application :: PARAM_CONTEXT => self :: package(),
                        self :: PARAM_ACTION => Manager :: ACTION_ICAL,
                        User :: PROPERTY_SECURITY_TOKEN => $this->getUserCalendar()->get_security_token()));

                $html = array();

                $content = array();

                $content[] = Display :: normal_message(
                    Translation :: get('ICalExternalMessage', array('URL' => $icalExternalUrl->getUrl())));
                $content[] = Display :: warning_message(
                    Translation :: get('ICalDownloadMessage', array('URL' => $icalDownloadUrl->getUrl())));

                $tabs = $this->getTabs();
                $tabs->set_content(implode(PHP_EOL, $content));

                $html[] = $this->render_header();
                $html[] = $tabs->render();
                $html[] = $this->render_footer();

                return implode(PHP_EOL, $html);
            }
        }
    }

    /**
     *
     * @return \Chamilo\Application\Calendar\Service\CalendarRendererProvider
     */
    private function getCalendarRendererProvider()
    {
        if (! isset($this->calendarRendererProvider))
        {
            $this->calendarRendererProvider = new CalendarRendererProvider(
                $this->getUserCalendar(),
                $this->get_user(),
                array());
        }

        return $this->calendarRendererProvider;
    }

    private function renderCalendar()
    {
        $icalRenderer = new ICalRenderer($this->getCalendarRendererProvider());
        $icalRenderer->renderAndSend();
    }
}