<?php
namespace Ehb\Application\Atlantis\Application\Right\Form;

use Chamilo\Libraries\Format\Form\FormValidator;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Utilities\Utilities;
use Ehb\Application\Atlantis\Application\Right\Table\DataClass\Right;
use Ehb\Application\Atlantis\Role\Entitlement\Storage\DataClass\Entitlement;

class RightForm extends FormValidator
{

    private $right;

    public function __construct($right, $action)
    {
        parent :: __construct('right', 'post', $action);
        
        $this->right = $right;
        $this->build();
        $this->setDefaults();
    }

    public function build()
    {
        $this->addElement(
            'static', 
            null, 
            Translation :: get('Application'), 
            $this->right->get_application()->get_name());
        
        $this->addElement('text', Right :: PROPERTY_NAME, Translation :: get('RightName'), array("size" => "50"));
        $this->addRule(
            Right :: PROPERTY_NAME, 
            Translation :: get('ThisFieldIsRequired', null, Utilities :: COMMON_LIBRARIES), 
            'required');
        $this->add_html_editor(Right :: PROPERTY_DESCRIPTION, Translation :: get('RightDescription'), true);
        $this->addElement('text', Right :: PROPERTY_CODE, Translation :: get('RightCode'), array("size" => "50"));
        
        if ($this->right->get_id())
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
        $right = $this->right;
        $defaults[Right :: PROPERTY_NAME] = $right->get_name();
        $defaults[Right :: PROPERTY_DESCRIPTION] = $right->get_description();
        $defaults[Entitlement :: PROPERTY_RIGHT_ID] = $right->get_id();
        $defaults[Right :: PROPERTY_CODE] = $right->get_code();
        
        parent :: setDefaults($defaults);
    }
}
