<?php
namespace application\ehb_helpdesk;

use common\libraries\ObjectTableOrder;
use common\libraries\PatternMatchCondition;
use common\libraries\FormValidator;
use common\libraries\Utilities;
use common\libraries\Translation;

class TicketForm extends FormValidator
{
    const PROPERTY_ID = 'id';
    const PROPERTY_QUEUE = 'Queue';
    const PROPERTY_FCKEDITORENCODED = 'FCKeditorEncoded';
    const PROPERTY_REQUESTOR = 'Requestors';
    const PROPERTY_CC = 'Cc';
    const PROPERTY_SUBJECT = 'Subject';
    const PROPERTY_FACULTY = 'Object-RT::Ticket--CustomField-5-Values';
    const PROPERTY_TRAINING = 'Object-RT::Ticket--CustomField-4-Values';
    const PROPERTY_TYPE = 'Object-RT::Ticket--CustomField-3-Values';
    const PROPERTY_URL = 'Object-RT::Ticket--CustomField-6-Value';
    const PROPERTY_SYSTEM = 'Object-RT::Ticket--CustomField-7-Values';
    const PROPERTY_MAGIC_FACULTY = 'Object-RT::Ticket--CustomField-5-Values-Magic';
    const PROPERTY_MAGIC_TRAINING = 'Object-RT::Ticket--CustomField-4-Values-Magic';
    const PROPERTY_MAGIC_TYPE = 'Object-RT::Ticket--CustomField-3-Values-Magic';
    const PROPERTY_MAGIC_URL = 'Object-RT::Ticket--CustomField-6-Value-Magic';
    const PROPERTY_MAGIC_SYSTEM = 'Object-RT::Ticket--CustomField-7-Values-Magic';
    const PROPERTY_ISSUE = 'Content';
    const PROPERTY_CONTENT_TYPE = 'ContentType';
    const PROPERTY_ATTACHMENT = 'Attach';
    const HELPDESK_QUEUE_ID = 16;
    const HELPDESK_NEW = 'new';
    const HELPDESK_FCKEDITOR = 1;
    const HELPDESK_CONTENT_TYPE = 'text/html';

    private $component;

    function __construct($component)
    {
        parent :: __construct('ticket', 'post', 'http://helpdesk.ehb.be/rt/SelfService/Create2.html');

        $this->component = $component;
        $this->build();
        $this->setDefaults();
    }

    function build()
    {
        $this->addElement('hidden', self :: PROPERTY_QUEUE, self :: HELPDESK_QUEUE_ID);
        $this->addElement('hidden', self :: PROPERTY_ID, self :: HELPDESK_NEW);
        // $this->addElement('hidden', self :: PROPERTY_FCKEDITORENCODED, self :: HELPDESK_FCKEDITOR);
        $this->addElement('hidden', self :: PROPERTY_SYSTEM);
        $this->addElement('hidden', self :: PROPERTY_REQUESTOR);
        $this->addElement('hidden', self :: PROPERTY_CONTENT_TYPE, self :: HELPDESK_CONTENT_TYPE);

        // Magic fields
        $this->addElement('hidden', self :: PROPERTY_MAGIC_FACULTY, 1);
        $this->addElement('hidden', self :: PROPERTY_MAGIC_TRAINING, 1);
        $this->addElement('hidden', self :: PROPERTY_MAGIC_TYPE, 1);
        $this->addElement('hidden', self :: PROPERTY_MAGIC_URL, 1);
        $this->addElement('hidden', self :: PROPERTY_MAGIC_SYSTEM, 1);

        // General
        $this->addElement('category', Translation :: get('General'));
        $this->addElement('static', null, Translation :: get('Requestor'), $this->component->get_user()->get_email());
        $this->addElement('text', self :: PROPERTY_SUBJECT, Translation :: get('Subject'), array("size" => "50"));
        $this->addElement('category');

        $this->addElement('category', Translation :: get('AdditionalInformation'));

        // Ticket type
        $type_options = array();
        $type_options['bug'] = 'Bug';
        $type_options['feature'] = 'Feature';
        $type_options['support'] = 'Support';
        $type_options['usability'] = 'Usability';
        $type_options['andere'] = 'Andere';

        $this->addElement('select', self :: PROPERTY_TYPE, Translation :: get('Type'), $type_options);

        $this->addElement('static', null, null, Translation :: get('FacultyTrainingAutomatic'));

        // Faculty
        $condition = new PatternMatchCondition(\group\Group :: PROPERTY_CODE, 'DEP_*');
        $groups = \group\DataManager :: get_instance()->retrieve_groups($condition, null, null,
                array(new ObjectTableOrder(\group\Group :: PROPERTY_NAME)));

        $faculty_options = array();
        $faculty_options['Centrale Administratie'] = 'Centrale Administratie';

        while ($group = $groups->next_result())
        {
            $faculty_options[$group->get_name()] = $group->get_name();
        }

        $this->addElement('select', self :: PROPERTY_FACULTY, Translation :: get('Faculty'), $faculty_options);

        // Training
        $condition = new PatternMatchCondition(\group\Group :: PROPERTY_CODE, 'TRA_OP_*');
        $groups = \group\DataManager :: get_instance()->retrieve_groups($condition, null, null,
                array(new ObjectTableOrder(\group\Group :: PROPERTY_NAME)));

        $training_options = array();
        $training_options['Andere'] = 'Andere';

        while ($group = $groups->next_result())
        {
            $training_options[$group->get_name()] = $group->get_name();
        }

        $this->addElement('select', self :: PROPERTY_TRAINING, Translation :: get('Training'), $training_options);
        $this->addElement('text', self :: PROPERTY_URL, Translation :: get('Url'), array("size" => "100"));
//         $this->addElement('text', self :: PROPERTY_CC, Translation :: get('Cc'), array("size" => "50"));
        $this->addElement('category');

        // Issue
        $this->addElement('category', Translation :: get('Issue'));
        $this->add_html_editor(self :: PROPERTY_ISSUE, Translation :: get('IssueDescription'), true);
        $this->addElement('file', self :: PROPERTY_ATTACHMENT, Translation :: get('Attachment'));
        $this->addElement('category');

        $buttons[] = $this->createElement('style_submit_button', 'submit',
                Translation :: get('Create', null, Utilities :: COMMON_LIBRARIES), array('class' => 'positive'));
        $buttons[] = $this->createElement('style_reset_button', 'reset',
                Translation :: get('Reset', null, Utilities :: COMMON_LIBRARIES), array('class' => 'normal empty'));

        $this->addGroup($buttons, 'buttons', null, '&nbsp;', false);
    }

    /**
     * Sets default values.
     *
     * @param $defaults array Default values for this form's parameters.
     */
    function setDefaults($defaults = array ())
    {
        $defaults[self :: PROPERTY_REQUESTOR] = $this->component->get_user()->get_email();
        $defaults[self :: PROPERTY_SYSTEM] = $_SERVER['HTTP_USER_AGENT'];
        $defaults[self :: PROPERTY_URL] = $_SERVER['HTTP_REFERER'];

        $groups = $this->component->get_user()->get_groups();

        if (! is_null($groups))
        {
            $faculties = array();
            $trainings = array();

            while ($group = $groups->next_result())
            {
                if ($group->get_code() != '')
                {
                    if (substr($group->get_code(), 0, 4) == 'DEP_')
                    {
                        if (! in_array($group->get_name(), $faculties))
                        {
                            $faculties[] = $group->get_name();
                        }
                    }
                    elseif (substr($group->get_code(), 0, 4) == 'TRA_')
                    {
                        if (! in_array($group->get_name(), $trainings))
                        {
                            $trainings[] = $group->get_name();
                        }
                    }
                }
            }

            $defaults[self :: PROPERTY_FACULTY] = $faculties[0];
            $defaults[self :: PROPERTY_TRAINING] = $trainings[0];
        }

        parent :: setDefaults($defaults);
    }
}
?>