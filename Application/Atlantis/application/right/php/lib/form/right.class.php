<?php
namespace application\atlantis\application\right;

use common\libraries\FormValidator;
use common\libraries\Utilities;
use common\libraries\Translation;

class RightForm extends FormValidator
{
    private $right;

    function __construct($right, $action)
    {
        parent :: __construct('right', 'post', $action);
        
        $this->right = $right;
        $this->build();
        $this->setDefaults();
    }

    function build()
    {
        $this->addElement('static', null, Translation :: get('Application'), $this->right->get_application()->get_name());
        
        $this->addElement('text', Right :: PROPERTY_NAME, Translation :: get('RightName'), array("size" => "50"));
        $this->addRule(Right :: PROPERTY_NAME, Translation :: get('ThisFieldIsRequired', null, Utilities :: COMMON_LIBRARIES), 'required');
        $this->add_html_editor(Right :: PROPERTY_DESCRIPTION, Translation :: get('RightDescription'), true);
        
        if ($this->right->get_id())
        {
            $buttons[] = $this->createElement('style_submit_button', 'submit', Translation :: get('Update', null, Utilities :: COMMON_LIBRARIES), array(
                    'class' => 'positive update'));
        }
        else
        {
            $buttons[] = $this->createElement('style_submit_button', 'submit', Translation :: get('Create', null, Utilities :: COMMON_LIBRARIES), array(
                    'class' => 'positive'));
        }
        $buttons[] = $this->createElement('style_reset_button', 'reset', Translation :: get('Reset', null, Utilities :: COMMON_LIBRARIES), array(
                'class' => 'normal empty'));
        
        $this->addGroup($buttons, 'buttons', null, '&nbsp;', false);
    }

    /**
     * Sets default values.
     *
     * @param $defaults array
     *            Default values for this form's parameters.
     */
    function setDefaults($defaults = array ())
    {
        $right = $this->right;
        $defaults[Right :: PROPERTY_NAME] = $right->get_name();
        $defaults[Right :: PROPERTY_DESCRIPTION] = $right->get_description();
        parent :: setDefaults($defaults);
    }
}
?>