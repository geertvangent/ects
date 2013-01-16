<?php
namespace application\discovery\module\employment;

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

class Module extends \application\discovery\Module
{

    /**
     *
     * @var multitype:\application\discovery\module\employment\employment
     */
    private $employments;
    const PARAM_USER_ID = 'user_id';

    function __construct(Application $application, ModuleInstance $module_instance)
    {
        parent :: __construct($application, $module_instance);
    }

    function get_module_parameters()
    {
        $parameter = self :: module_parameters();
        if (! $parameter->get_user_id())
        {
            $parameter->set_user_id($this->get_application()->get_user_id());
        }
        return $parameter;
    }

    static function module_parameters()
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
     *
     * @return multitype:\application\discovery\module\employment\employment
     */
    function get_employments()
    {
        if (! isset($this->employments))
        {
            $this->employments = DataManager :: get_instance($this->get_module_instance())->retrieve_employments(
                    $this->get_module_parameters());
        }
        return $this->employments;
    }

    function has_data($parameters = null)
    {
        $parameters = $parameters ? $parameters : $this->get_module_parameters();
        return $this->get_data_manager()->count_employments($parameters);
    }
    
    /*
     * (non-PHPdoc) @see application\discovery.Module::render()
     */
    function render()
    {
        $html = array();
        
        $data = array();
        
        foreach ($this->employments as $key => $employment)
        {
            $row = array();
            $row[] = $employment->get_year();
            $row[] = $employment->get_training();
            
            $class = 'employment" style="" id="employment_' . $key;
            $details_action = new ToolbarItem(Translation :: get('ShowCourses'), 
                    Theme :: get_common_image_path() . 'action_details.png', '#', ToolbarItem :: DISPLAY_ICON, false, 
                    $class);
            $row[] = $details_action->as_html();
            $data[] = $row;
        }
        
        // $path = Path :: namespace_to_full_path(__NAMESPACE__, true) . 'resources/javascript/employment.js';
        // $html[] = ResourceManager :: get_instance()->get_resource_html($path);
        
        return implode("\n", $html);
    }

    function get_type()
    {
        return ModuleInstance :: TYPE_USER;
    }

    static function get_available_implementations()
    {
        $types = array();
        
        $modules = Filesystem :: get_directory_content(
                Path :: namespace_to_full_path(__NAMESPACE__) . 'implementation/', Filesystem :: LIST_DIRECTORIES, false);
        foreach ($modules as $module)
        {
            $namespace = __NAMESPACE__ . '\implementation\\' . $module;
            $types[] = $namespace;
        }
        return $types;
    }
}
?>