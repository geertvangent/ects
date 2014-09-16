<?php
namespace application\atlantis\rights;

use libraries\EqualityCondition;
use libraries\Translation;
use libraries\FormValidator;
use libraries\Utilities;
use libraries\AdvancedElementFinderElementTypes;
use libraries\AdvancedElementFinderElements;
use core\rights\NewUserEntity;
use core\rights\NewPlatformGroupEntity;
use libraries\StaticConditionVariable;
use libraries\PropertyConditionVariable;

class RightsForm extends FormValidator
{
    const PROPERTY_ACCESS = 'targets';

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
        $types = new AdvancedElementFinderElementTypes();
        $types->add_element_type(NewUserEntity :: get_element_finder_type());
        $types->add_element_type(NewPlatformGroupEntity :: get_element_finder_type());
        $this->addElement('advanced_element_finder', self :: PROPERTY_ACCESS, null, $types);

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
        $default_elements = new AdvancedElementFinderElements();
        $targets_entities = Rights :: get_instance()->get_access_targets_entities();
        $user_entity = NewUserEntity :: get_instance();
        $group_entity = NewPlatformGroupEntity :: get_instance();

        foreach ($targets_entities[NewUserEntity :: ENTITY_TYPE] as $entity)
        {
            $default_elements->add_element($user_entity->get_element_finder_element($entity));
        }

        foreach ($targets_entities[NewPlatformGroupEntity :: ENTITY_TYPE] as $entity)
        {
            $default_elements->add_element($group_entity->get_element_finder_element($entity));
        }

        $this->getElement(self :: PROPERTY_ACCESS)->setDefaultValues($default_elements);

        parent :: setDefaults(array());
    }

    public function set_rights()
    {
        $values = $this->exportValues();

        $rights_util = Rights :: get_instance();
        $location = $rights_util->get_access_root();

        $targets_entities = Rights :: get_instance()->get_access_targets_entities();

        $location_id = $location->get_id();

        if (! isset($values[self :: PROPERTY_ACCESS][NewUserEntity :: ENTITY_TYPE]))
        {
            $values[self :: PROPERTY_ACCESS][NewUserEntity :: ENTITY_TYPE] = array();
        }

        if (! isset($values[self :: PROPERTY_ACCESS][NewPlatformGroupEntity :: ENTITY_TYPE]))
        {
            $values[self :: PROPERTY_ACCESS][NewPlatformGroupEntity :: ENTITY_TYPE] = array();
        }

        foreach ($values[self :: PROPERTY_ACCESS] as $entity_type => $target_ids)
        {
            $to_delete = array_diff((array) $targets_entities[$entity_type], $target_ids);
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

            foreach ($to_delete as $target_id)
            {
                $location_entity_right = Rights :: get_instance()->get_access_location_entity_right(
                    $target_id,
                    $entity_type);

                $condition = new EqualityCondition(
                    new PropertyConditionVariable(
                        RightsLocationEntityRightGroup :: class_name(),
                        RightsLocationEntityRightGroup :: PROPERTY_LOCATION_ENTITY_RIGHT_ID),
                    new StaticConditionVariable($location_entity_right->get_id()));

                if (! DataManager :: deletes(RightsLocationEntityRightGroup :: class_name(), $condition))
                {
                    return false;
                }

                if (! $rights_util->invert_access_location_entity_right(
                    Rights :: VIEW_RIGHT,
                    $target_id,
                    $entity_type,
                    $location_id))
                {
                    return false;
                }
            }
        }

        return true;
    }
}
