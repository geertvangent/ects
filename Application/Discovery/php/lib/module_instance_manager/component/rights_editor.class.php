<?php
namespace application\discovery;

use application\discovery\rights_editor_manager\RightsEditorManager;
use common\libraries\Request;

class ModuleInstanceManagerRightsEditorComponent extends ModuleInstanceManager
{

    private $module_instance_id;

    private $namespace;

    public function run()
    {
        $this->module_instance_id = Request :: get(DiscoveryManager :: PARAM_MODULE_ID);
        $module_instance = DataManager :: get_instance()->retrieve_module_instance($this->module_instance_id);
        $this->namespace = '\\' . $module_instance->get_type() . '\Rights';
        RightsEditorManager :: launch($this);
    }

    public function get_available_rights()
    {
        $namespace = $this->namespace;
        return $namespace :: get_available_rights();
    }

    public function get_additional_parameters()
    {
        $parameters[] = DiscoveryManager :: PARAM_MODULE_ID;
        return $parameters;
    }

    public function get_module_instance_id()
    {
        return $this->module_instance_id;
    }
}
