<?php
namespace application\discovery\rights_editor_manager;

use application\discovery\UserEntity;

use common\libraries\Theme;

use common\libraries\FormValidator;
use common\libraries\Translation;
use common\libraries\Utilities;
use common\libraries\ResourceManager;
use common\libraries\Path;
use common\libraries\EqualityCondition;
use common\libraries\Session;

use common\libraries\AdvancedElementFinderElementTypes;
use common\libraries\AdvancedElementFinderElements;
use application\discovery\RightsGroupEntityRight;
use rights\PlatformGroupEntity;

use rights\RightsDataManager;
use rights\RightsLocation;
use rights\RightsUtil;

/**
 * Form to display the rights on a more usable way with radio buttons.
 *
 * @author Sven Vanpoucke
 * @package application.common.rights_editor_manager.component
 */

class ManageForm extends FormValidator
{
    const PROPERTY_RIGHT_OPTION = 'right_option';
    
    const PROPERTY_SUBMIT = 'submit';
    const PROPERTY_RESET = 'reset';
    const PROPERTY_BUTTONS = 'buttons';
    const PROPERTY_TARGETS = 'targets';
    const PROPERTY_GROUP_USE = 'group_use';
    const PROPERTY_ACTION = 'action';
    const PROPERTY_RIGHT = 'right';
    
    const ACTION_GRANT = 1;
    const ACTION_DENY = 2;
    
    const RIGHT_OPTION_ALL = 0;
    const RIGHT_OPTION_ME = 1;
    const RIGHT_OPTION_SELECT = 2;
    
    /**
     * The context for the rights form
     */
    private $context;
    
    /**
     * The available rights
     * @var Array<Int>
     */
    private $available_rights;

    function __construct($action, $available_rights)
    {
        parent :: __construct('manager', 'post', $action);
        
        $this->available_rights = $available_rights;
        
        $this->build_form();
    }

    /**
     * Builds the form
     */
    function build_form()
    {
        $this->build_right_form();
        
        $this->build_form_footer();
    }

    /**
     * Builds the form for a given right
     *
     * @param String $right_name
     * @param int $right_id
     */
    private function build_right_form()
    {
        $this->addElement('category', ' ');
        
        $element_template = array();
        $element_template[] = '<div class="column">';
        $element_template[] = '<div class="element"><!-- BEGIN error --><span class="form_error">{error}</span><br /><!-- END error -->	{element}</div>';
        $element_template[] = '</div>';
        $element_template = implode("\n", $element_template);
        
        $this->addElement('select', self :: PROPERTY_ACTION, '', array(
                self :: ACTION_GRANT => Translation :: get('Grant'), self :: ACTION_DENY => Translation :: get('Deny')));
        
        $this->get_renderer()->setElementTemplate($element_template, self :: PROPERTY_ACTION);
        
        $this->addElement('select', self :: PROPERTY_RIGHT, '', array_flip($this->available_rights), 'multiple');
        $this->get_renderer()->setElementTemplate($element_template, self :: PROPERTY_RIGHT);
        
        $group = array();
        
        $group[] = & $this->createElement('radio', null, null, Translation :: get('Everyone'), self :: RIGHT_OPTION_ALL, array(
                'class' => 'other_option_selected'));
        $group[] = & $this->createElement('radio', null, null, Translation :: get('OnlyForMe'), self :: RIGHT_OPTION_ME, array(
                'class' => 'other_option_selected'));
        $group[] = & $this->createElement('radio', null, null, Translation :: get('SelectSpecificEntities'), self :: RIGHT_OPTION_SELECT, array(
                'class' => 'entity_option_selected'));
        
        $this->addGroup($group, self :: PROPERTY_RIGHT_OPTION, '', '<br />');
        $this->get_renderer()->setElementTemplate($element_template, self :: PROPERTY_RIGHT_OPTION);
        
        // Add the advanced element finder
        

        $user_entity = new UserEntity();
        
        $this->entities = array();
        $this->entities[UserEntity :: ENTITY_TYPE] = $user_entity;
        $this->entities[PlatformGroupEntity :: ENTITY_TYPE] = new PlatformGroupEntity();
        
        $types = new AdvancedElementFinderElementTypes();
        
        foreach ($this->entities as $entity)
        {
            $types->add_element_type($entity->get_element_finder_type());
        }
        $this->addElement('category');
        $this->addElement('category', Translation :: get('SelectUserAndGroups'), 'entity_selector_box');
        $element_template = '{element}';
        
        $this->addElement('advanced_element_finder', self :: PROPERTY_TARGETS, null, $types);
        $this->get_renderer()->setElementTemplate($element_template, self :: PROPERTY_TARGETS);
        $this->addElement('category');
        
        // Add the advanced element finder for groups uses
        $types = new AdvancedElementFinderElementTypes();
        $platform_group_entity = new PlatformGroupEntity();
        $types->add_element_type($platform_group_entity->get_element_finder_type());
        
        $this->addElement('category', Translation :: get('ModuleInfoAbout'));
        $element_template = '{element}';
        
        $this->addElement('advanced_element_finder', self :: PROPERTY_GROUP_USE, null, $types);
        $this->get_renderer()->setElementTemplate($element_template, self :: PROPERTY_GROUP_USE);
        $this->addElement('category');
    
    }

    /**
     * Builds the form footer
     */
    private function build_form_footer()
    {
        $buttons = array();
        
        $buttons[] = $this->createElement('style_submit_button', self :: PROPERTY_SUBMIT, Translation :: get('Submit', null, Utilities :: COMMON_LIBRARIES), array(
                'class' => 'positive update'));
        
        $buttons[] = $this->createElement('style_reset_button', self :: PROPERTY_RESET, Translation :: get('Reset', null, Utilities :: COMMON_LIBRARIES), array(
                'class' => 'normal empty'));
        
        $this->addGroup($buttons, self :: PROPERTY_BUTTONS, null, '&nbsp;', false);
        
        $this->addElement('html', ResourceManager :: get_instance()->get_resource_html(Path :: get_common_extensions_path(true) . 'new_rights_editor_manager/resources/javascript/rights_form.js'));
    }

    /**
     * Handles the rights options for the specific location
     * @param RightsLocation $location
     */
    function handle_rights()
    {
        $values = $this->exportValues();
        $succes = true;
        
        $option = $values[self :: PROPERTY_RIGHT_OPTION];
        $action = $values[self :: PROPERTY_ACTION];
        foreach ($values[self :: PROPERTY_RIGHT] as $right_id)
        {
            foreach ($values[self :: PROPERTY_GROUP_USE] as $group_type => $group_ids)
            {
                foreach ($group_ids as $group_id)
                {
                    switch ($option)
                    {
                        case self :: RIGHT_OPTION_ALL :
                            if ($action == self :: ACTION_GRANT)
                            {
                                $right = new RightsGroupEntityRight();
                                $right->set_entity_id(0);
                                $right->set_entity_type(0);
                                $right->set_group_id($group_id);
                                $right->set_module_id(1);
                                $right->set_right_id($right_id);
                                $succes &= $right->create();
                            }
                            
                            break;
                        case self :: RIGHT_OPTION_ME :
                            if ($action == self :: ACTION_GRANT)
                            {
                                $right = new RightsGroupEntityRight();
                                $right->set_entity_id(Session :: get_user_id());
                                $right->set_entity_type(1);
                                $right->set_group_id($group_id);
                                $right->set_module_id(1);
                                $right->set_right_id($right_id);
                                $succes &= $right->create();
                            }
                            break;
                        case self :: RIGHT_OPTION_SELECT :
                            foreach ($values[self :: PROPERTY_TARGETS] as $entity_type => $target_ids)
                            {
                                foreach ($target_ids as $target_id)
                                {
                                    if ($action == self :: ACTION_GRANT)
                                    {
                                        $right = new RightsGroupEntityRight();
                                        $right->set_entity_id($target_id);
                                        $right->set_entity_type($entity_type);
                                        $right->set_group_id($group_id);
                                        $right->set_module_id(1);
                                        $right->set_right_id($right_id);
                                        $succes &= $right->create();
                                    }
                                }
                            }
                    }
                }
            }
        }
        
        return $succes;
    }

}
?>