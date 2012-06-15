<?php
namespace application\atlantis\role\entity;

use common\libraries\AdvancedElementFinderElementType;

use common\libraries\AdvancedElementFinderElementTypes;
use rights\PlatformGroupEntity;
use rights\UserEntity;
use common\libraries\FormValidator;
use common\libraries\Utilities;
use common\libraries\Translation;

class EntityForm extends FormValidator
{
    private $application;
    
    const PROPERTY_ENTITY = 'entity';
    const PROPERTY_ROLE = 'role';
    const PROPERTY_CONTEXT = 'context';

    function __construct($application, $action)
    {
        parent :: __construct('application', 'post', $action);
        
        $this->application = $application;
        $this->build();
        $this->setDefaults();
    }

    function build()
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
        
        $types->add_element_type(new AdvancedElementFinderElementType('roles', Translation :: get('Roles'), 'application\atlantis\role', 'roles_feed', array()));
        
        $this->addElement('advanced_element_finder', self :: PROPERTY_ROLE, Translation :: get('Roles'), $types);
        
        // context
        $types = new AdvancedElementFinderElementTypes();
        
        $types->add_element_type(new AdvancedElementFinderElementType('contexts', Translation :: get('Contexts'), 'application\atlantis\context', 'contexts_feed', array()));
        
        $this->addElement('advanced_element_finder', self :: PROPERTY_CONTEXT, Translation :: get('Contexts'), $types);
        
        // buttons
        $buttons[] = $this->createElement('style_submit_button', 'submit', Translation :: get('Create', null, Utilities :: COMMON_LIBRARIES), array(
                'class' => 'positive'));
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
        parent :: setDefaults($defaults);
    }
}
?>