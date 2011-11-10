<?php
namespace application\discovery\module\course;

use common\libraries\Request;

use common\libraries\Theme;
use common\libraries\SortableTableFromArray;
use common\libraries\Translation;
use common\libraries\PropertiesTable;
use common\libraries\Display;
use common\libraries\Application;

use application\discovery\SortableTable;
use application\discovery\ModuleInstance;
use application\discovery\module\profile\DataManager;

class Module extends \application\discovery\Module
{
    const PARAM_PROGRAMME_ID = 'programme_id';
    
    /**
     * @var \application\discovery\module\course\Course
     */
    private $course;

    function __construct(Application $application, ModuleInstance $module_instance)
    {
        parent :: __construct($application, $module_instance);
    }

    function get_data_manager()
    {
        return DataManager :: get_instance($this->get_module_instance());
    }

    function get_course_parameters()
    {
        return new Parameters(Request :: get(self :: PARAM_PROGRAMME_ID));
    }

    /**
     * @return \application\discovery\module\course\Course
     */
    function get_course()
    {
        if (! isset($this->course))
        {
            $this->course = $this->get_data_manager()->retrieve_course($this->get_course_parameters());
        }
        return $this->course;
    }

    /* (non-PHPdoc)
     * @see application\discovery.Module::render()
     */
    function render()
    {
        $html = array();
        
        return implode("\n", $html);
    }
}
?>