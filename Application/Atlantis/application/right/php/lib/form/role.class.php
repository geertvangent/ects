<?php
namespace application\atlantis\application\right;

use application\atlantis\role\entitlement\Entitlement;
use common\libraries\FormValidator;
use common\libraries\Utilities;
use common\libraries\Translation;

class RoleForm extends FormValidator
{

    private $right;

    public function __construct($application, $right, $action)
    {
        parent :: __construct('role', 'post', $action);

        $this->right = $right;
        $this->application = $application;
        $this->build();
        $this->setDefaults();
    }

    public function build()
    {
        $this->addElement('static', null, Translation :: get('Application'), $this->application);
        $this->addElement('static', null, Translation :: get('Right'), $this->right);

        $buttons[] = $this->createElement(
            'style_submit_button',
            'submit',
            Translation :: get('Create', null, Utilities :: COMMON_LIBRARIES),
            array('class' => 'positive'));

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
        $defaults[Entitlement :: PROPERTY_RIGHT_ID] = $this->right;
        parent :: setDefaults($defaults);
    }
}
