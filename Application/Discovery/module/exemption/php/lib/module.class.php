<?php
namespace application\discovery\module\exemption;

use common\libraries\Filesystem;

use common\libraries\Request;

use common\libraries\Path;
use common\libraries\WebApplication;
use common\libraries\ResourceManager;
use common\libraries\ToolbarItem;
use common\libraries\Theme;
use common\libraries\Translation;
use common\libraries\PropertiesTable;
use common\libraries\Display;
use common\libraries\Application;

use application\discovery\SortableTable;
use application\discovery\ModuleInstance;
use application\discovery\module\profile\DataManager;

class Module extends \application\discovery\Module
{
    const PARAM_USER_ID = 'user_id';
    /**
     * @var multitype:\application\discovery\module\exemption\TeachingAssignment
     */
    private $exemptions;

    function __construct(Application $application, ModuleInstance $module_instance)
    {
        parent :: __construct($application, $module_instance);
    
    }

    function get_exemption_parameters()
    {
        $parameter = self :: get_module_parameters();
        if (! $parameter->get_user_id())
        {
            $parameter->set_user_id($this->get_application()->get_user_id());
        }
        return $parameter;
    }

    static function get_module_parameters()
    {
        $param_user = Request :: get(self :: PARAM_USER_ID);
        $parameter = new Parameters();
        if ($param_user)
        {
            $parameter->set_user_id($param_user);
        }
        return $parameter;
    }

    /**
     * @return multitype:\application\discovery\module\exemption\TeachingAssignment
     */
    function get_exemptions()
    {
        if (! isset($this->exemptions))
        {
            $this->exemptions = DataManager :: get_instance($this->get_module_instance())->retrieve_exemptions($this->get_exemption_parameters());
        }
        return $this->exemptions;
    }

    /* (non-PHPdoc)
     * @see application\discovery.Module::render()
     */
    function render()
    {
        $html = array();
        $data = array();
        
        foreach ($this->exemptions as $key => $exemption)
        {
            $row = array();
            $row[] = $exemption->get_year();
            $row[] = $exemption->get_training();
            $row[] = $exemption->get_name();
            
            $class = 'exemption" style="" id="exemption_' . $key;
            $details_action = new ToolbarItem(Translation :: get('ShowTeachingAssignments'), Theme :: get_common_image_path() . 'action_details.png', '#', ToolbarItem :: DISPLAY_ICON, false, $class);
            $row[] = $details_action->as_html();
            $data[] = $row;
        }
        return implode("\n", $html);
    }

    function get_type()
    {
        return ModuleInstance :: TYPE_USER;
    }

    static function get_available_implementations()
    {
        $types = array();
        
        $modules = Filesystem :: get_directory_content(Path :: namespace_to_full_path(__NAMESPACE__) . 'implementation/', Filesystem :: LIST_DIRECTORIES, false);
        foreach ($modules as $module)
        {
            $namespace = __NAMESPACE__ . '\implementation\\' . $module;
            $types[] = $namespace;
        }
        return $types;
    }
}
?>