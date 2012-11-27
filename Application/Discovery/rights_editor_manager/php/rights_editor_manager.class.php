<?php
namespace application\discovery\rights_editor_manager;

use common\libraries\SubManager;
use common\libraries\Translation;
use Exception;
use application\discovery\PlatformGroupEntity;

/**
 * Rights editor manager for unlimited amount of entities.
 * With simple and advanced interface.
 *
 * @package application.common.rights_editor_manager
 * @author Sven Vanpoucke
 */
class RightsEditorManager extends SubManager
{
    // Parameters
    const PARAM_ACTION = 'rights_action';
    
    const PARAM_ENTITY_TYPE = 'entity_type';
    const PARAM_ENTITY_ID = 'entity_id';
    const PARAM_RIGHT_ID = 'right_id';
    
    // Actions
    const ACTION_EDIT_ADVANCED_RIGHTS = 'advanced_rights_editor';
    const ACTION_MANAGE = 'manager';
    const ACTION_SET_ENTITY_RIGHTS = 'entity_rights_setter';
    
    /**
     * Cached selected entity
     */
    private $selected_entity;
    
    // Default functions
    
    /**
     * Launches the rights editor with the additional properties
     * 
     * @param $application Application           
     */
    static function launch($application)
    {
        parent :: launch(__CLASS__, $application);    
    }

    /**
     * Returns the parameter with which this submanager's actions are described
     * 
     * @return String
     */
    static function get_action_parameter()
    {
        return self :: PARAM_ACTION;
    }

    /**
     * Returns the default action for this submannager
     * 
     * @return String
     */
    static function get_default_action()
    {
        return self :: ACTION_MANAGE;
    }

    /**
     * Returns the path to the components folder
     * 
     * @return String
     */
    function get_application_component_path()
    {
        return dirname(__FILE__) . '/component/';
    }
    
    /*
     * Builds the url to browse an entity @param int $entity_type @return String
     */
    
    function get_entity_url($entity_type)
    {
        return $this->get_url(array(self :: PARAM_ENTITY_TYPE => $entity_type));
    }
    
    // Delegation
    
    /**
     * Retrieves the available rights
     * 
     * @return Array
     */
    function get_available_rights()
    {
        return $this->get_parent()->get_available_rights();
    }

    /**
     * Retrieves additional information from the parent application
     * 
     * @return String
     */
    function get_additional_information()
    {
        if (method_exists($this->get_parent(), 'get_additional_information'))
        {
            return $this->get_parent()->get_additional_information();
        }
    }
    
    // Helper functions
    
    /**
     * Gets the selected entity type and if no type selected, uses the first
     * available entity
     * 
     * @return String
     */
    function get_selected_entity()
    {
        return new PlatformGroupEntity();
        
        // if (! $this->selected_entity)
        // {
        // $selected_entity = $this->get_parameter(self :: PARAM_ENTITY_TYPE);
        // if ($selected_entity)
        // {
        // $this->selected_entity = $this->entities[$selected_entity];
        // }
        // else
        // {
        // $first_entity = array_shift($this->get_entities());
        // if ($first_entity)
        // {
        // array_unshift($this->get_entities(), $first_entity);
        // $this->selected_entity = $first_entity;
        // }
        // }
        // }
        //
        // return $this->selected_entity;
    }

}

?>