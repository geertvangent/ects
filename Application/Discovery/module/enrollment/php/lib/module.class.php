<?php
namespace application\discovery\module\enrollment;

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
     * @var multitype:\application\discovery\module\enrollment\Enrollment
     */
    private $enrollments;
    
    const PARAM_USER_ID = 'user_id';

    function __construct(Application $application, ModuleInstance $module_instance)
    {
        parent :: __construct($application, $module_instance);
    }

    function get_enrollment_parameters()
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
     * @return multitype:\application\discovery\module\enrollment\Enrollment
     */
    function get_enrollments()
    {
        if (! isset($this->enrollments))
        {
            $this->enrollments = DataManager :: get_instance($this->get_module_instance())->retrieve_enrollments($this->get_enrollment_parameters());
        }
        return $this->enrollments;
    }

    /* (non-PHPdoc)
     * @see application\discovery.Module::render()
     */
    function render()
    {
        $html = array();
        
        $data = array();
        
        foreach ($this->enrollments as $key => $enrollment)
        {
            $row = array();
            $row[] = $enrollment->get_year();
            $row[] = $enrollment->get_training();
            
            $class = 'enrollment" style="" id="enrollment_' . $key;
            $details_action = new ToolbarItem(Translation :: get('ShowCourses'), Theme :: get_common_image_path() . 'action_details.png', '#', ToolbarItem :: DISPLAY_ICON, false, $class);
            $row[] = $details_action->as_html();
            $data[] = $row;
        }
        
        //        $path = Path :: namespace_to_full_path(__NAMESPACE__, true) . 'resources/javascript/enrollment.js';
        //        $html[] = ResourceManager :: get_instance()->get_resource_html($path);
        

        return implode("\n", $html);
    }
}
?>