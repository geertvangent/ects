<?php
namespace Ehb\Application\Atlantis\Role\Form;

use Chamilo\Libraries\Format\Form\FormValidator;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Utilities\Utilities;
use Ehb\Application\Atlantis\Role\Storage\DataClass\Role;

class RoleForm extends FormValidator
{

    private $role;

    public function __construct($role, $action)
    {
        parent::__construct('role', 'post', $action);
        
        $this->role = $role;
        $this->build();
        $this->setDefaults();
    }

    public function build()
    {
        $this->addElement('text', Role::PROPERTY_NAME, Translation::get('RoleName'), array("size" => "50"));
        $this->addRule(
            Role::PROPERTY_NAME, 
            Translation::get('ThisFieldIsRequired', null, Utilities::COMMON_LIBRARIES), 
            'required');
        $this->add_html_editor(Role::PROPERTY_DESCRIPTION, Translation::get('RoleDescription'), true);
        
        if ($this->role->get_id())
        {
            $buttons[] = $this->createElement(
                'style_submit_button', 
                'submit', 
                Translation::get('Update', null, Utilities::COMMON_LIBRARIES), 
                null, 
                null, 
                'arrow-right');
        }
        else
        {
            $buttons[] = $this->createElement(
                'style_submit_button', 
                'submit', 
                Translation::get('Create', null, Utilities::COMMON_LIBRARIES));
        }
        $buttons[] = $this->createElement(
            'style_reset_button', 
            'reset', 
            Translation::get('Reset', null, Utilities::COMMON_LIBRARIES));
        
        $this->addGroup($buttons, 'buttons', null, '&nbsp;', false);
    }

    /**
     * Sets default values.
     * 
     * @param $defaults array Default values for this form's parameters.
     */
    public function setDefaults($defaults = array ())
    {
        $role = $this->role;
        $defaults[Role::PROPERTY_NAME] = $role->get_name();
        $defaults[Role::PROPERTY_DESCRIPTION] = $role->get_description();
        parent::setDefaults($defaults);
    }
}
