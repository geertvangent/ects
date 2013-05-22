<?php
namespace application\atlantis\rights;

use common\libraries\AndCondition;
use common\libraries\EqualityCondition;
use rights\NewUserEntity;
use rights\NewPlatformGroupEntity;
use common\libraries\Translation;
use common\libraries\FormValidator;
use common\libraries\Utilities;
use common\libraries\AdvancedElementFinderElementTypes;
use common\libraries\AdvancedElementFinderElements;

class RightsGroupForm extends FormValidator
{
    const PROPERTY_ACCESS = 'access';
    const PROPERTY_TARGETS = 'targets';

    private $form_user;

    public function __construct($form_user, $action)
    {
        parent :: __construct('rights', 'post', $action);
        
        $this->form_user = $form_user;
        $this->build_form();
        $this->setDefaults();
    }

    public function build_form()
    {
        $element_template = array();
        $element_template[] = '<div class="row">';
        $element_template[] = '<div class="element"><!-- BEGIN error --><span class="form_error">{error}</span><br /><!-- END error -->	{element}</div>';
        $element_template[] = '<div class="form_feedback"></div>';
        $element_template[] = '<div class="clear">&nbsp;</div>';
        $element_template[] = '</div>';
        $element_template = implode("\n", $element_template);
        
        $this->addElement('category', Translation :: get('RightsGroupAccess'));
        $types = new AdvancedElementFinderElementTypes();
        $types->add_element_type(NewUserEntity :: get_element_finder_type());
        $types->add_element_type(NewPlatformGroupEntity :: get_element_finder_type());
        $this->addElement('advanced_element_finder', self :: PROPERTY_ACCESS, null, $types);
        $this->get_renderer()->setElementTemplate($element_template, self :: PROPERTY_ACCESS);
        $this->addElement('category');
        
        $this->addElement('category', Translation :: get('RightsGroupTargets'));
        $types = new AdvancedElementFinderElementTypes();
        $types->add_element_type(NewPlatformGroupEntity :: get_element_finder_type());
        $this->addElement('advanced_element_finder', self :: PROPERTY_TARGETS, null, $types);
        $this->get_renderer()->setElementTemplate($element_template, self :: PROPERTY_TARGETS);
        $this->addElement('category');
        
        $buttons[] = $this->createElement(
            'style_submit_button', 
            'submit', 
            Translation :: get('Save', null, Utilities :: COMMON_LIBRARIES), 
            array('class' => 'positive save'));
        $buttons[] = $this->createElement(
            'style_reset_button', 
            'reset', 
            Translation :: get('Reset', null, Utilities :: COMMON_LIBRARIES), 
            array('class' => 'normal empty'));
        
        $this->addGroup($buttons, 'buttons', null, '&nbsp;', false);
    }

    public function setDefaults()
    {
        // $default_elements = new AdvancedElementFinderElements();
        // $targets_entities = Rights :: get_instance()->get_quota_targets_entities();
        // $user_entity = NewUserEntity :: get_instance();
        // $group_entity = NewPlatformGroupEntity :: get_instance();
        
        // foreach ($targets_entities[NewUserEntity :: ENTITY_TYPE] as $entity)
        // {
        // $default_elements->add_element($user_entity->get_element_finder_element($entity));
        // }
        
        // foreach ($targets_entities[NewPlatformGroupEntity :: ENTITY_TYPE] as $entity)
        // {
        // $default_elements->add_element($group_entity->get_element_finder_element($entity));
        // }
        
        // $this->getElement(self :: PROPERTY_ACCESS)->setDefaultValues($default_elements);
        parent :: setDefaults(array());
    }

    public function set_rights()
    {
        $values = $this->exportValues();
        
        $rights_util = Rights :: get_instance();
        $location = $rights_util->get_quota_root();
        
        $targets_entities = Rights :: get_instance()->get_quota_targets_entities();
        $location_id = $location->get_id();
        
        foreach ($values[self :: PROPERTY_ACCESS] as $entity_type => $target_ids)
        {
            $to_add = array_diff($target_ids, (array) $targets_entities[$entity_type]);
            
            foreach ($to_add as $target_id)
            {
                if (! $rights_util->invert_quota_location_entity_right(
                    Rights :: VIEW_RIGHT, 
                    $target_id, 
                    $entity_type, 
                    $location_id))
                {
                    return false;
                }
            }
            
            foreach ($target_ids as $target_id)
            {
                $location_entity_right = Rights :: get_instance()->get_quota_location_entity_right(
                    $target_id, 
                    $entity_type);
                
                foreach ($values[self :: PROPERTY_TARGETS][NewPlatformGroupEntity :: ENTITY_TYPE] as $group_id)
                {
                    $conditions = array();
                    $conditions[] = new EqualityCondition(
                        LocationEntityRightGroup :: PROPERTY_LOCATION_ENTITY_RIGHT_ID, 
                        $location_entity_right->get_id());
                    $conditions[] = new EqualityCondition(LocationEntityRightGroup :: PROPERTY_GROUP_ID, $group_id);
                    $condition = new AndCondition($conditions);
                    
                    $existing_right_group = DataManager :: retrieve(
                        LocationEntityRightGroup :: class_name(), 
                        $condition);
                    
                    if (! $existing_right_group instanceof LocationEntityRightGroup)
                    {
                        $new_right_group = new LocationEntityRightGroup();
                        $new_right_group->set_location_entity_right_id($location_entity_right->get_id());
                        $new_right_group->set_group_id($group_id);
                        
                        if (! $new_right_group->create())
                        {
                            return false;
                        }
                    }
                }
            }
        }
        
        return true;
    }
}
