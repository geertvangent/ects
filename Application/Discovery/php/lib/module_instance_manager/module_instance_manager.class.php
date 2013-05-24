<?php
namespace application\discovery;

use common\libraries\CommonDataManager;
use common\libraries\WebApplication;
use common\libraries\Request;
use common\libraries\SubManager;
use common\libraries\EqualityCondition;
use common\libraries\OrCondition;
use common\libraries\AndCondition;
use common\libraries\Registration;

class ModuleInstanceManager extends SubManager
{
    const PARAM_INSTANCE_ACTION = 'action';
    const PARAM_TYPE = 'type';
    const PARAM_CONTENT_TYPE = 'content_type';
    const ACTION_BROWSE_INSTANCES = 'browser';
    const ACTION_ACTIVATE_INSTANCE = 'activator';
    const ACTION_DEACTIVATE_INSTANCE = 'deactivator';
    const ACTION_UPDATE_INSTANCE = 'updater';
    const ACTION_DELETE_INSTANCE = 'deleter';
    const ACTION_CREATE_INSTANCE = 'creator';
    const ACTION_MANAGE_INSTANCE_RIGHTS = 'rights_editor';
    const ACTION_MOVE_INSTANCE = 'mover';
    const DEFAULT_ACTION = self :: ACTION_BROWSE_INSTANCES;

    public function __construct($repository_manager)
    {
        parent :: __construct($repository_manager);
        
        $instance_action = Request :: get(self :: PARAM_INSTANCE_ACTION);
        if ($instance_action)
        {
            $this->set_action($instance_action);
        }
    }

    public function set_action($action)
    {
        $this->set_parameter(self :: PARAM_INSTANCE_ACTION, $action);
    }

    public function get_action()
    {
        return $this->get_parameter(self :: PARAM_INSTANCE_ACTION);
    }

    public function get_application_component_path()
    {
        return WebApplication :: get_application_class_lib_path(DiscoveryManager :: APPLICATION_NAME) .
             'module_instance_manager/component/';
    }

    public static function launch($application)
    {
        parent :: launch(__CLASS__, $application);
    }

    /**
     * Helper function for the SubManager class, pending access to class constants via variables in PHP 5.3 e.g. $name =
     * $class :: DEFAULT_ACTION DO NOT USE IN THIS SUBMANAGER'S CONTEXT Instead use: - self :: DEFAULT_ACTION in the
     * context of this class - YourSubManager :: DEFAULT_ACTION in all other application classes
     */
    public static function get_default_action()
    {
        return self :: DEFAULT_ACTION;
    }

    /**
     * Helper function for the SubManager class, pending access to class constants via variables in PHP 5.3 e.g. $name =
     * $class :: PARAM_ACTION DO NOT USE IN THIS SUBMANAGER'S CONTEXT Instead use: - self :: PARAM_ACTION in the context
     * of this class - YourSubManager :: PARAM_ACTION in all other application classes
     */
    public static function get_action_parameter()
    {
        return self :: PARAM_INSTANCE_ACTION;
    }

    public static function get_registered_types($status = Registration :: STATUS_ACTIVE)
    {
        $instance_conditions = array();
        $instance_conditions[] = new EqualityCondition(
            Registration :: PROPERTY_TYPE, 
            Registration :: TYPE_EXTERNAL_REPOSITORY_MANAGER);
        $instance_conditions[] = new EqualityCondition(
            Registration :: PROPERTY_TYPE, 
            Registration :: TYPE_VIDEO_CONFERENCING_MANAGER);
        
        $conditions = array();
        $conditions[] = new OrCondition($instance_conditions);
        $conditions[] = new EqualityCondition(Registration :: PROPERTY_STATUS, $status);
        $condition = new AndCondition($conditions);
        
        return CommonDataManager :: retrieves(Registration :: class_name(), $condition);
    }

    public static function exists($instance_type, $type)
    {
        $manager_class = self :: get_manager_class($instance_type);
        return $manager_class :: exists($type);
    }
}
