<?php
namespace application\atlantis\role\entity;

use common\libraries\Path;
use common\libraries\ResourceManager;
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
                'application\atlantis\role', 
                'roles_feed', 
                array()));
        
        $this->addElement('advanced_element_finder', self :: PROPERTY_ROLE, Translation :: get('Roles'), $types);
        
        // context
        $types = new AdvancedElementFinderElementTypes();
        
        $types->add_element_type(
            new AdvancedElementFinderElementType(
                'contexts', 
                Translation :: get('Contexts'), 
                'application\atlantis\context', 
                'contexts_feed', 
                array()));
        
        $this->addElement('advanced_element_finder', self :: PROPERTY_CONTEXT, Translation :: get('Contexts'), $types);
        
        // start date
        $this->addElement(
            'text', 
            self :: PROPERTY_START_DATE, 
            Translation :: get('StartDate'), 
            'id="start_date" style="width:120px;"');
        // $this->addGroup($start_date, Task :: PROPERTY_START_DATE, Translation :: get('StartDate'), '<br />', false);
        // $this->get_renderer()->setGroupElementTemplate('{element}', Task :: PROPERTY_START_DATE);
        
        // end date
        $this->addElement(
            'text', 
            self :: PROPERTY_END_DATE, 
            Translation :: get('EndDate'), 
            'id="end_date" style="width:120px;"');
        // $this->addGroup($end_date, Task :: PROPERTY_END_DATE, Translation :: get('EndDate'), '<br />', false);
        // $this->get_renderer()->setGroupElementTemplate('{element}', Task :: PROPERTY_END_DATE);
        
        // buttons
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
        
        $this->addElement(
            'html', 
            ResourceManager :: get_instance()->get_resource_html(
                Path :: namespace_to_full_path(__NAMESPACE__, true) . 'resources/javascript/dates.js'));
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
