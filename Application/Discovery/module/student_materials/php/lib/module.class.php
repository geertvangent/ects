<?php
namespace application\discovery\module\student_materials;

use common\libraries\Path;

use common\libraries\Filesystem;

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
    /**
     * @var multitype:\application\discovery\module\career\Course
     */
    private $courses;
    
    const PARAM_USER_ID = 'user_id';

    function __construct(Application $application, ModuleInstance $module_instance)
    {
        parent :: __construct($application, $module_instance);
    }

    function get_data_manager()
    {
        return DataManager :: get_instance($this->get_module_instance());
    }

    function get_student_materials_parameters()
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
     * @return multitype:\application\discovery\module\career\Course
     */
    function get_courses()
    {
        return $this->courses;
    }

    /**
     * @return multitype:multitype:string
     */
    function get_table_data()
    {
        $data = array();
        
        foreach ($this->courses as $course)
        {
            $row = array();
            $row[] = $course->get_year();
            $row[] = $course->get_name();
            $data[] = $row;
        }
        
        return $data;
    }

    /**
     * @return multitype:string
     */
    function get_table_headers()
    {
        $headers = array();
        $headers[] = array(Translation :: get('Year'), 'class="code"');
        $headers[] = array(Translation :: get('Course'));
        
        foreach ($this->get_mark_moments() as $mark_moment)
        {
            $headers[] = array($mark_moment->get_name());
        }
        
        return $headers;
    }

    /* (non-PHPdoc)
     * @see application\discovery.Module::render()
     */
    function render()
    {
        $html = array();
        
        $table = new SortableTable($this->get_table_data());
        
        foreach ($this->get_table_headers() as $header_id => $header)
        {
            $table->set_header($header_id, $header[0], false);
            
            if ($header[1])
            {
                $table->getHeader()->setColAttributes($header_id, $header[1]);
            }
        }
        
        $html[] = $table->toHTML();
        
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