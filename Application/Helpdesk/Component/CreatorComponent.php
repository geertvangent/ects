<?php
namespace Ehb\Application\Helpdesk\Component;

use Chamilo\Configuration\Configuration;
use Chamilo\Libraries\Platform\Translation;
use Ehb\Application\Helpdesk\Form\TicketForm;
use Ehb\Application\Helpdesk\Manager;
use GuzzleHttp\Post\PostFile;

// require_once Path :: getInstance()->getPluginPath() . 'pear/HTTP/Request2.php';
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
            $url = Configuration::getInstance()->get_setting(array(self::package(), 'url'));
            $username = Configuration::getInstance()->get_setting(array(self::package(), 'username'));
            $password = Configuration::getInstance()->get_setting(array(self::package(), 'password'));
            $queue = Configuration::getInstance()->get_setting(array(self::package(), 'queue'));
            
            $values = $form->exportValues();
            
            $request_tracker = new \GuzzleHttp\Client(['base_url' => $url . '/REST/1.0/']);
            $request_tracker->setDefaultOption('verify', false);
            
            $ticket_values = array();
            
            $ticket_values['Queue'] = $queue;
            $ticket_values['Requestor'] = $values[TicketForm::PROPERTY_REQUESTOR];
            $ticket_values['Subject'] = $values[TicketForm::PROPERTY_SUBJECT];
            $ticket_values['Text'] = $values[TicketForm::PROPERTY_ISSUE];
            $ticket_values['CF-5'] = $values[TicketForm::PROPERTY_FACULTY];
            $ticket_values['CF-4'] = $values[TicketForm::PROPERTY_TRAINING];
            $ticket_values['CF-3'] = $values[TicketForm::PROPERTY_TYPE];
            $ticket_values['CF-6'] = $values[TicketForm::PROPERTY_URL];
            $ticket_values['CF-7'] = $values[TicketForm::PROPERTY_SYSTEM];
            $ticket_values['id'] = 'ticket/new';
            
            $content = array();
            $content['user'] = $username;
            $content['pass'] = $password;
            $content['content'] = $ticket_values;
            $content['Attachment'] = file_get_contents($_FILES[TicketForm::PROPERTY_ATTACHMENT]['tmp_name']);
            $content['attachment-1'] = file_get_contents($_FILES[TicketForm::PROPERTY_ATTACHMENT]['tmp_name']);
            
            $request = $request_tracker->createRequest('POST', 'ticket/new');
            
            $postBody = $request->getBody();
            $postBody->setField('user', $username);
            $postBody->setField('pass', $password);
            $postBody->setField('content', $this->array_to_url($ticket_values));
            
            $response = $request_tracker->send($request);
            
            if ($response->getStatusCode() == 200)
            {
                if ($_FILES[TicketForm::PROPERTY_ATTACHMENT]['error'] == UPLOAD_ERR_OK)
                {
                    preg_match_all('/# Ticket (.*) created\./', $response->getBody()->getContents(), $matches);
                    $ticket_id = $matches[1][0];
                    
                    $ticket_values = array();
                    $ticket_values['Action'] = 'comment';
                    $ticket_values['id'] = $ticket_id;
                    $ticket_values['Ticket'] = $ticket_id;
                    $ticket_values['Text'] = 'Attachment bij ticket verzonden via het helpdeskformulier';
                    $ticket_values['Attachment'] = $_FILES[TicketForm::PROPERTY_ATTACHMENT]['name'];
                    
                    $endpoint = 'ticket/' . $ticket_id . '/comment';
                    
                    $request = $request_tracker->createRequest('POST', $endpoint);
                    $postBody = $request->getBody();
                    $postBody->setField('user', $username);
                    $postBody->setField('pass', $password);
                    $postBody->setField('content', $this->array_to_url($ticket_values));
                    $postBody->addFile(
                        new PostFile(
                            'attachment_1', 
                            file_get_contents($_FILES[TicketForm::PROPERTY_ATTACHMENT]['tmp_name']), 
                            $_FILES[TicketForm::PROPERTY_ATTACHMENT]['name']));
                    
                    $response = $request_tracker->send($request);
                    
                    if ($response->getStatusCode() == '200')
                    {
                        $this->redirect(Translation::get('TicketSubmitted'), false, $this->get_parameters());
                    }
                    else
                    {
                        $html = array();
                        
                        $html[] = $this->render_header();
                        $html[] = $form->toHtml();
                        $html[] = $this->detect_browser_data();
                        $html[] = $this->render_footer();
                        
                        return implode(PHP_EOL, $html);
                    }
                }
                else
                {
                    $this->redirect(Translation::get('TicketSubmitted'), false, $this->get_parameters());
                }
            }
            else
            {
                $html = array();
                
                $html[] = $this->render_header();
                $html[] = $form->toHtml();
                $html[] = $this->detect_browser_data();
                $html[] = $this->render_footer();
                
                return implode(PHP_EOL, $html);
            }
        }
        else
        {
            $html = array();
            
            $html[] = $this->render_header();
            $html[] = $form->toHtml();
            $html[] = $this->detect_browser_data();
            $html[] = $this->render_footer();
            
            return implode(PHP_EOL, $html);
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

    public function array_to_url($data)
    {
        if (is_array($data))
        {
            $tmp = array();
            
            foreach ($data as $key => $value)
            {
                if (is_array($value))
                {
                    $subtmp = array();
                    
                    foreach ($value as $subkey => $subvalue)
                    {
                        $tmp[] = $key . '[]' . ': ' . str_replace("\n", "\n  ", str_replace("\r", '', $subvalue));
                    }
                }
                else
                {
                    $tmp[] = $key . ': ' . str_replace("\n", "\n  ", str_replace("\r", '', $value));
                }
            }
            return implode(chr(10), $tmp);
        }
    }
}
