<?php
namespace Ehb\Application\Helpdesk\Component;

use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\File\Properties\FileProperties;
use Chamilo\Libraries\Platform\Configuration\PlatformSetting;
use Chamilo\Libraries\File\Path;
use Ehb\Application\Helpdesk\Form\TicketForm;
use Ehb\Application\Helpdesk\Manager;
use Ehb\Application\Helpdesk\Rest\RestClient;

require_once Path :: get_plugin_path() . 'pear/HTTP/Request2.php';
class CreatorComponent extends Manager
{

    /**
     * Runs this component and displays its output.
     */
    public function run()
    {
        $form = new TicketForm($this);

        if ($form->validate())
        {
            $url = PlatformSetting :: get('url', __NAMESPACE__);
            $username = PlatformSetting :: get('username', __NAMESPACE__);
            $password = PlatformSetting :: get('password', __NAMESPACE__);
            $queue = PlatformSetting :: get('queue', __NAMESPACE__);

            $values = $form->exportValues();

            $request_tracker = new RestClient($url . '/REST/1.0/');
            $request_tracker->set_connexion_mode(RestClient :: MODE_CURL);
            // $request_tracker = new RequestTracker($url, $username, $password);

            $ticket_values = array();

            $ticket_values['Queue'] = $queue;
            $ticket_values['Requestor'] = $values[TicketForm :: PROPERTY_REQUESTOR];
            $ticket_values['Subject'] = $values[TicketForm :: PROPERTY_SUBJECT];
            $ticket_values['Text'] = $values[TicketForm :: PROPERTY_ISSUE];
            $ticket_values['CF-5'] = $values[TicketForm :: PROPERTY_FACULTY];
            $ticket_values['CF-4'] = $values[TicketForm :: PROPERTY_TRAINING];
            $ticket_values['CF-3'] = $values[TicketForm :: PROPERTY_TYPE];
            $ticket_values['CF-6'] = $values[TicketForm :: PROPERTY_URL];
            $ticket_values['CF-7'] = $values[TicketForm :: PROPERTY_SYSTEM];
            $ticket_values['id'] = 'ticket/new';

            $content = array();
            $content['user'] = $username;
            $content['pass'] = $password;
            $content['content'] = $request_tracker->array_to_url($ticket_values);
            $content['Attachment'] = file_get_contents($_FILES[TicketForm :: PROPERTY_ATTACHMENT]['tmp_name']);
            $content['attachment-1'] = file_get_contents($_FILES[TicketForm :: PROPERTY_ATTACHMENT]['tmp_name']);

            // $response = $request_tracker->createTicket($content);
            $response = $request_tracker->request(RestClient :: METHOD_POST, 'ticket/new', array('content' => $content));

            if ($response->get_response_http_code() == 200)
            {
                if ($_FILES[TicketForm :: PROPERTY_ATTACHMENT]['error'] == UPLOAD_ERR_OK)
                {
                    preg_match_all('/# Ticket (.*) created\./', $response->get_response_content(), $matches);
                    $ticket_id = $matches[1][0];

                    $ticket_values = array();
                    $ticket_values['Action'] = 'comment';
                    $ticket_values['id'] = $ticket_id;
                    $ticket_values['Ticket'] = $ticket_id;
                    $ticket_values['Text'] = 'Attachment bij ticket verzonden via het helpdeskformulier';
                    $ticket_values['Attachment'] = $_FILES[TicketForm :: PROPERTY_ATTACHMENT]['name'];

                    $endpoint = '/REST/1.0/ticket/' . $ticket_id . '/comment';

                    $file_properties = FileProperties :: from_path(
                        $_FILES[TicketForm :: PROPERTY_ATTACHMENT]['tmp_name']);

                    $request = new \HTTP_Request2($url . $endpoint);
                    $request->setMethod(\HTTP_Request2 :: METHOD_POST);
                    $request->addPostParameter('user', $username);
                    $request->addPostParameter('pass', $password);
                    $request->addPostParameter('content', $request_tracker->array_to_url($ticket_values));
                    $request->addUpload(
                        'attachment_1',
                        $_FILES[TicketForm :: PROPERTY_ATTACHMENT]['tmp_name'],
                        $_FILES[TicketForm :: PROPERTY_ATTACHMENT]['tmp_name'],
                        $file_properties->get_type());
                    $response = $request->send();

                    if ($response->getStatus() == '200')
                    {
                        $this->redirect(Translation :: get('TicketSubmitted'), false, $this->get_parameters());
                    }
                    else
                    {
                        $this->display_header();
                        $form->display();
                        $this->detect_browser_data();
                        $this->display_footer();
                    }
                }
                else
                {
                    $this->redirect(Translation :: get('TicketSubmitted'), false, $this->get_parameters());
                }
            }
            else
            {
                $this->display_header();
                $form->display();
                $this->detect_browser_data();
                $this->display_footer();
            }
        }
        else
        {
            $this->display_header();
            $form->display();
            $this->detect_browser_data();
            $this->display_footer();
        }
    }

    public function detect_browser_data()
    {
        return '<script type=text/javascript language=javascript>
<!-- Hide Javascript on old browsers

tmp = "Useragent: ' . $_SERVER['HTTP_USER_AGENT'] . '\n"
tmp += "Online:  "+navigator.onLine + "\n"
tmp += "Cookies Enabled:  "+navigator.cookieEnabled + "\n"
tmp += "Platform: "+navigator.platform + "\n"
tmp += "Cputype: "+navigator.cpuClass + "\n"
tmp += "Opsprofile: "+navigator.opsProfile + "\n"
tmp += "Systemlanguage: "+navigator.systemLanguage + "\n"
tmp += "Userlanguage: "+navigator.userLanguage + "\n"
tmp += "Userprofile: "+navigator.userProfile + "\n"

a=navigator.plugins
for (k=0; k<a.length; k++) {
tmp += "Plugin: "+ a[k].name + " - " + a[k].description  + "\n"
}

document.forms["ticket"].elements["Object-RT::Ticket--CustomField-7-Values"].value = tmp;

-->
</script>';
    }
}
