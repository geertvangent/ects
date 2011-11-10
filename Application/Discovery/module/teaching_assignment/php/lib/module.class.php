<?php
namespace application\discovery\module\teaching_assignment;

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
     * @var multitype:\application\discovery\module\teaching_assignment\TeachingAssignment
     */
    private $teaching_assignments;

    function __construct(Application $application, ModuleInstance $module_instance)
    {
        parent :: __construct($application, $module_instance);
    
    }

    function get_teaching_assignment_parameters()
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
     * @return multitype:\application\discovery\module\teaching_assignment\TeachingAssignment
     */
    function get_teaching_assignments()
    {
        if (! isset($this->teaching_assignments))
        {
            $this->teaching_assignments = DataManager :: get_instance($this->get_module_instance())->retrieve_teaching_assignments($this->get_teaching_assignment_parameters());
        }
        return $this->teaching_assignments;
    }

    /* (non-PHPdoc)
     * @see application\discovery.Module::render()
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
            $details_action = new ToolbarItem(Translation :: get('ShowTeachingAssignments'), Theme :: get_common_image_path() . 'action_details.png', '#', ToolbarItem :: DISPLAY_ICON, false, $class);
            $row[] = $details_action->as_html();
            $data[] = $row;
        }
        return implode("\n", $html);
    }
}
?>