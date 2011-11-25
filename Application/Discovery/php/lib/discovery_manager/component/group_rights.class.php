<?php
namespace application\discovery;

use common\libraries\Utilities;
use common\libraries\Display;
use common\libraries\DelegateComponent;
use common\libraries\Request;
use common\libraries\BreadcrumbTrail;
use common\libraries\Breadcrumb;
use common\libraries\Translation;

use application\discovery\rights_editor_manager\RightsEditorManager;

/**
 * @author Hans De Bisschop
 * @package application.phrases
 */

class DiscoveryManagerGroupRightsComponent extends DiscoveryManager implements DelegateComponent
{
    private $module_instance_id;
    private $namespace;

    function run()
    {
        $this->module_instance_id = Request :: get(self :: PARAM_MODULE_ID);
        $module_instance = DiscoveryDataManager :: get_instance()->retrieve_module_instance($this->module_instance_id);
        $this->namespace = '\\' . $module_instance->get_type() . '\Rights';
        RightsEditorManager :: launch($this);
    }

    function get_available_rights()
    {
        $namespace = $this->namespace;
        return $namespace :: get_available_rights();
    }

    function get_additional_parameters()
    {
        $parameters[] = self :: PARAM_MODULE_ID;
        return $parameters;
    }
}
?>