<?php
namespace application\discovery\data_source;

use application\discovery\rights_editor_manager\RightsEditorManager;
use common\libraries\Request;

class RightsEditorComponent extends Manager
{

    private $instance_id;

    private $namespace;

    public function run()
    {
        $this->instance_id = Request :: get(Manager :: PARAM_DATA_SOURCE_ID);
        $instance = DataManager :: retrieve_by_id(Instance :: class_name(), (int) $this->instance_id);
        $this->namespace = '\\' . $instance->get_type() . '\Rights';
        
        RightsEditorManager :: launch($this);
    }

    public function get_available_rights()
    {
        $namespace = $this->namespace;
        return $namespace :: get_available_rights();
    }

    public function get_additional_parameters()
    {
        $parameters[] = self :: PARAM_DATA_SOURCE_ID;
        return $parameters;
    }

    public function get_instance_id()
    {
        return $this->instance_id;
    }
}
