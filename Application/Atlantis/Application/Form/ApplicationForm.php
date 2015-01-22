<?php
namespace Ehb\Application\Atlantis\Application\Form;

use Chamilo\Libraries\Format\Form\FormValidator;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Utilities\Utilities;
use Ehb\Application\Atlantis\Application\Storage\DataClass\Application;

class ApplicationForm extends FormValidator
{

    private $application;

    public function __construct($application, $action)
    {
        parent :: __construct('application', 'post', $action);

        $this->application = $application;
        $this->build();
        $this->setDefaults();
    }

    public function build()
    {
        $this->addElement(
            'text',
            Application :: PROPERTY_NAME,
            Translation :: get('ApplicationName'),
            array("size" => "50"));
        $this->addRule(
            Application :: PROPERTY_NAME,
            Translation :: get('ThisFieldIsRequired', null, Utilities :: COMMON_LIBRARIES),
            'required');
        $this->add_html_editor(Application :: PROPERTY_DESCRIPTION, Translation :: get('ApplicationDescription'), true);
        $this->addElement(
            'text',
            Application :: PROPERTY_URL,
            Translation :: get('ApplicationUrl'),
            array("size" => "100"));
        $this->addElement(
            'text',
            Application :: PROPERTY_CODE,
            Translation :: get('ApplicationCode'),
            array("size" => "50"));

        if ($this->application->get_id())
        {
            $buttons[] = $this->createElement(
                'style_submit_button',
                'submit',
                Translation :: get('Update', null, Utilities :: COMMON_LIBRARIES),
                array('class' => 'positive update'));
        }
        else
        {
            $buttons[] = $this->createElement(
                'style_submit_button',
                'submit',
                Translation :: get('Create', null, Utilities :: COMMON_LIBRARIES),
                array('class' => 'positive'));
        }
        $buttons[] = $this->createElement(
            'style_reset_button',
            'reset',
            Translation :: get('Reset', null, Utilities :: COMMON_LIBRARIES),
            array('class' => 'normal empty'));

        $this->addGroup($buttons, 'buttons', null, '&nbsp;', false);
    }

    /**
     * Sets default values.
     *
     * @param $defaults array Default values for this form's parameters.
     */
    public function setDefaults($defaults = array ())
    {
        $application = $this->application;
        $defaults[Application :: PROPERTY_NAME] = $application->get_name();
        $defaults[Application :: PROPERTY_DESCRIPTION] = $application->get_description();
        $defaults[Application :: PROPERTY_URL] = $application->get_url();
        $defaults[Application :: PROPERTY_CODE] = $application->get_code();

        parent :: setDefaults($defaults);
    }
}
