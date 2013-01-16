<?php
namespace application\discovery\module\teaching_assignment;

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
    const PARAM_YEAR = 'year';

    /**
     *
     * @var multitype:\application\discovery\module\teaching_assignment\TeachingAssignment
     */
    private $teaching_assignments;

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
        $year = Request :: get(self :: PARAM_YEAR);
        $parameter = new Parameters();
        if ($param_user)
        {
            $parameter->set_user_id($param_user);
        }
        
        if ($year)
        {
            $parameter->set_year($year);
        }
        return $parameter;
    }

    /**
     *
     * @return multitype:\application\discovery\module\teaching_assignment\TeachingAssignment
     */
    function get_teaching_assignments($parameters)
    {
        $year = $parameters->get_year();
        $user_id = $parameters->get_user_id();
        
        if (! isset($this->teaching_assignments[$user_id][$year]))
        {
            $this->teaching_assignments[$user_id][$year] = DataManager :: get_instance($this->get_module_instance())->retrieve_teaching_assignments(
                    $parameters);
        }
        return $this->teaching_assignments[$user_id][$year];
    }

    function has_data($parameters = null)
    {
        $parameters = $parameters ? $parameters : $this->get_teaching_assignment_parameters();
        return $this->get_data_manager()->count_teaching_assignments($parameters);
    }
    
    /*
     * (non-PHPdoc) @see application\discovery.Module::render()
     */
    function render()
    {
        $html = array();
        $data = array();
        
        foreach ($this->teaching_assignments as $key => $teaching_assignment)
        {
            $row = array();
            $row[] = $teaching_assignment->get_year();
            $row[] = $teaching_assignment->get_training();
            $row[] = $teaching_assignment->get_name();
            
            $class = 'teaching_assignment" style="" id="teaching_assignment_' . $key;
            $details_action = new ToolbarItem(Translation :: get('ShowTeachingAssignments'), 
                    Theme :: get_common_image_path() . 'action_details.png', '#', ToolbarItem :: DISPLAY_ICON, false, 
                    $class);
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