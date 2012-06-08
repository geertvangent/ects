<?php
namespace application\atlantis\role\entitlement;

use common\libraries\Utilities;

use common\libraries\Translation;

use common\libraries\EqualityCondition;

use common\libraries\DataClassRetrievesParameters;

use common\libraries\FormValidator;

class EntitlementForm extends FormValidator
{
    private $component;

    function __construct($component, $action)
    {
        parent :: __construct('entitlement', 'post', $action);
        
        $this->component = $component;
        $this->build();
        $this->setDefaults();
    }

    function build()
    {
        $parameters = new DataClassRetrievesParameters(new EqualityCondition(\application\atlantis\application\right\Right :: PROPERTY_APPLICATION_ID, $this->component->get_application_id()));
        $rights = \application\atlantis\application\right\DataManager :: retrieves(\application\atlantis\application\right\Right :: class_name(), $parameters);
        while ($right = $rights->next_result())
        {
            $this->addElement('checkbox', 'right[' . $right->get_id() . ']', $right->get_name(), $right->get_description());
        }
        
        $buttons[] = $this->createElement('style_submit_button', 'submit', Translation :: get('Grant'), array(
                'class' => 'positive grant'));
        
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
        $parameters = new DataClassRetrievesParameters(new EqualityCondition(Entitlement :: PROPERTY_ROLE_ID, $this->component->get_role_id()));
        $entitlements = DataManager :: retrieves(Entitlement :: class_name(), $parameters);
        while ($entitlement = $entitlements->next_result())
        {
            if ($entitlement->get_right()->get_application_id() == $this->component->get_application_id())
            {
                $defaults['right'][$entitlement->get_right_id()] = 1;
            }
        }
        
        parent :: setDefaults($defaults);
    }
}
?>