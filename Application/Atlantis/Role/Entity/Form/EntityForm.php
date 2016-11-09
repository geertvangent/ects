<?php
namespace Ehb\Application\Atlantis\Role\Entity\Form;

use Chamilo\Libraries\File\Path;
use Chamilo\Libraries\Format\Form\Element\AdvancedElementFinder\AdvancedElementFinderElementType;
use Chamilo\Libraries\Format\Form\Element\AdvancedElementFinder\AdvancedElementFinderElementTypes;
use Chamilo\Libraries\Format\Form\FormValidator;
use Chamilo\Libraries\Format\Utilities\ResourceManager;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Utilities\Utilities;
use Ehb\Application\Atlantis\Role\Entity\Entities\PlatformGroupEntity;
use Ehb\Application\Atlantis\Role\Entity\Entities\UserEntity;
use Ehb\Application\Atlantis\Role\Manager;

class EntityForm extends FormValidator
{

    private $application;
    const PROPERTY_ENTITY = 'entity';
    const PROPERTY_ROLE = 'role';
    const PROPERTY_CONTEXT = 'context';
    const PROPERTY_START_DATE = 'start_date';
    const PROPERTY_END_DATE = 'end_date';

    public function __construct($application, $action)
    {
        parent :: __construct('application', 'post', $action);

        $this->application = $application;
        $this->build();
        $this->setDefaults();
    }

    public function build()
    {
        // entity
        $user_entity = new UserEntity();

        $entities = array();
        $entities[UserEntity :: ENTITY_TYPE] = $user_entity;
        $entities[PlatformGroupEntity :: ENTITY_TYPE] = new PlatformGroupEntity();

        $types = new AdvancedElementFinderElementTypes();

        foreach ($entities as $entity)
        {
            $types->add_element_type($entity->get_element_finder_type());
        }

        $this->addElement('advanced_element_finder', self :: PROPERTY_ENTITY, Translation :: get('UserGroup'), $types);

        // roles
        $types = new AdvancedElementFinderElementTypes();

        $types->add_element_type(
            new AdvancedElementFinderElementType(
                'roles',
                Translation :: get('Roles'),
                Manager :: context() . '\Ajax',
                'roles_feed',
                array()));

        $this->addElement('advanced_element_finder', self :: PROPERTY_ROLE, Translation :: get('Roles'), $types);

        // context
        $types = new AdvancedElementFinderElementTypes();

        $types->add_element_type(
            new AdvancedElementFinderElementType(
                'contexts',
                Translation :: get('Contexts'),
                Manager :: context() . '\Entity\Ajax',
                'contexts_feed',
                array()));

        $this->addElement('advanced_element_finder', self :: PROPERTY_CONTEXT, Translation :: get('Contexts'), $types);

        // start date
        $this->addElement(
            'text',
            self :: PROPERTY_START_DATE,
            Translation :: get('StartDate'),
            'id="start_date" style="width:120px;"');
        $this->addRule(
            self :: PROPERTY_START_DATE,
            Translation :: get('ThisFieldIsRequired', null, Utilities :: COMMON_LIBRARIES),
            'required');

        // end date
        $this->addElement(
            'text',
            self :: PROPERTY_END_DATE,
            Translation :: get('EndDate'),
            'id="end_date" style="width:120px;"');
        $this->addRule(
            self :: PROPERTY_END_DATE,
            Translation :: get('ThisFieldIsRequired', null, Utilities :: COMMON_LIBRARIES),
            'required');

        // buttons
        $buttons[] = $this->createElement(
            'style_submit_button',
            'submit',
            Translation :: get('Create', null, Utilities :: COMMON_LIBRARIES));
        $buttons[] = $this->createElement(
            'style_reset_button',
            'reset',
            Translation :: get('Reset', null, Utilities :: COMMON_LIBRARIES));

        $this->addGroup($buttons, 'buttons', null, '&nbsp;', false);

        $this->addElement(
            'html',
            ResourceManager :: getInstance()->get_resource_html(
                Path :: getInstance()->getJavascriptPath('Ehb\Application\Atlantis\Role\Entity', true) . 'dates.js'));
    }

    /**
     * Sets default values.
     *
     * @param $defaults array Default values for this form's parameters.
     */
    public function setDefaults($defaults = array ())
    {
        parent :: setDefaults($defaults);
    }
}
