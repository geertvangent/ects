<?php
namespace application\discovery;

use rights\NewPlatformGroupEntity;

use rights\NewUserEntity;

use common\libraries\Utilities;
use common\libraries\Display;
use common\libraries\DelegateComponent;
use common\libraries\Request;
use common\libraries\BreadcrumbTrail;
use common\libraries\Breadcrumb;
use common\libraries\Translation;

use common\extensions\new_rights_editor_manager\RightsEditorManager;

/**
 *
 * @author Hans De Bisschop
 * @package application.phrases
 */

class DiscoveryManagerRightsComponent extends DiscoveryManager implements DelegateComponent
{
    private $module_instance_id;
    private $namespace;

    function run()
    {
        $this->module_instance_id = Request :: get(self :: PARAM_MODULE_ID);
        $module_instance = DiscoveryDataManager :: get_instance()->retrieve_module_instance($this->module_instance_id);
        $this->namespace = '\\' . $module_instance->get_type() . '\Rights';
        $namespace = $this->namespace;
        RightsEditorManager :: launch($this, 'discovery_' . $this->module_instance_id, $this->get_locations(), $this->get_entities());
    }

    function get_locations()
    {
        $namespace = $this->namespace;
        $locations = array();
        
        $locations[] = $namespace :: get_instance()->get_current_location($this->module_instance_id);
        return $locations;
    }

    function get_entities()
    {
        $excluded_users[] = $this->get_user_id();
        
        $user_entity = new NewUserEntity();
        
        $entities = array();
        $entities[NewUserEntity :: ENTITY_TYPE] = $user_entity;
        $entities[NewPlatformGroupEntity :: ENTITY_TYPE] = new NewPlatformGroupEntity();
        
        return $entities;
    }

    function get_available_rights()
    {
        $namespace = $this->namespace;
        return $namespace :: get_available_rights();
    }

    function get_additional_parameters()
    {
        $module_instance = DiscoveryDataManager :: get_instance()->retrieve_module_instance(Request :: get(self :: PARAM_MODULE_ID));
        $namespace = '\\' . $module_instance->get_type() . '\Module';
        $parameters = $namespace :: get_module_parameters();
        $parameters = array_keys($parameters->get_parameters());
        $parameters[] = self :: PARAM_MODULE_ID;
        return $parameters;
    }
}
?>