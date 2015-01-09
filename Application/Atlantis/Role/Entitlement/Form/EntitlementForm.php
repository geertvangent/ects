<?php
namespace Chamilo\Application\Atlantis\Role\Entitlement\Form;

use Chamilo\Libraries\Utilities\Utilities;
use Chamilo\Libraries\Platform\Translation\Translation;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;
use Chamilo\Libraries\Format\Form\FormValidator;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Chamilo\Application\Atlantis\Role\Entitlement\Storage\DataClass\Entitlement;
use Chamilo\Application\Atlantis\Role\Entitlement\Storage\DataManager;

class EntitlementForm extends FormValidator
{

    private $component;

    public function __construct($component, $action)
    {
        parent :: __construct('entitlement', 'post', $action);

        $this->component = $component;
        $this->build();
        $this->setDefaults();
    }

    public function build()
    {
        $parameters = new DataClassRetrievesParameters(
            new EqualityCondition(
                new PropertyConditionVariable(
                    \Chamilo\Application\Atlantis\Application\Right\Table\DataClass\Right :: class_name(),
                    \Chamilo\Application\Atlantis\Application\Right\Table\DataClass\Right :: PROPERTY_APPLICATION_ID),
                new StaticConditionVariable($this->component->get_application_id())));
        $rights = \Chamilo\Application\Atlantis\Application\Right\Table\DataManager :: retrieves(
            \Chamilo\Application\Atlantis\Application\Right\Table\DataClass\Right :: class_name(),
            $parameters);
        while ($right = $rights->next_result())
        {
            $this->addElement(
                'checkbox',
                'right[' . $right->get_id() . ']',
                $right->get_name(),
                $right->get_description());
        }

        $buttons[] = $this->createElement(
            'style_submit_button',
            'submit',
            Translation :: get('Grant'),
            array('class' => 'positive grant'));

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
        $parameters = new DataClassRetrievesParameters(
            new EqualityCondition(
                new PropertyConditionVariable(Entitlement :: class_name(), Entitlement :: PROPERTY_ROLE_ID),
                new StaticConditionVariable($this->component->get_role_id())));
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
