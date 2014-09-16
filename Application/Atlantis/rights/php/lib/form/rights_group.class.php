<?php
namespace application\atlantis\rights;

use libraries\AndCondition;
use libraries\EqualityCondition;
use libraries\Translation;
use libraries\FormValidator;
use libraries\Utilities;
use libraries\AdvancedElementFinderElementTypes;
use core\rights\NewPlatformGroupEntity;
use core\rights\NewUserEntity;

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

    public function set_rights()
    {
        $values = $this->exportValues();
        
        $rights_util = Rights :: get_instance();
        $location = $rights_util->get_access_root();
        
        $targets_entities = Rights :: get_instance()->get_access_targets_entities();
        $location_id = $location->get_id();
        
        foreach ($values[self :: PROPERTY_ACCESS] as $entity_type => $target_ids)
        {
            $to_add = array_diff($target_ids, (array) $targets_entities[$entity_type]);
            
            foreach ($to_add as $target_id)
            {
                if (! $rights_util->invert_access_location_entity_right(
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
                $location_entity_right = Rights :: get_instance()->get_access_location_entity_right(
                    $target_id, 
                    $entity_type);
                
                foreach ($values[self :: PROPERTY_TARGETS][NewPlatformGroupEntity :: ENTITY_TYPE] as $group_id)
                {
                    $conditions = array();
                    $conditions[] = new EqualityCondition(
                        RightsLocationEntityRightGroup :: PROPERTY_LOCATION_ENTITY_RIGHT_ID, 
                        $location_entity_right->get_id());
                    $conditions[] = new EqualityCondition(RightsLocationEntityRightGroup :: PROPERTY_GROUP_ID, $group_id);
                    $condition = new AndCondition($conditions);
                    
                    $existing_right_group = DataManager :: retrieve(
                        RightsLocationEntityRightGroup :: class_name(), 
                        $condition);
                    
                    if (! $existing_right_group instanceof RightsLocationEntityRightGroup)
                    {
                        $new_right_group = new RightsLocationEntityRightGroup();
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
