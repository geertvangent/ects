<?php
namespace application\discovery\module\course_results;

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
    const PARAM_PROGRAMME_ID = 'programme_id';

    /**
     *
     * @var multitype:\application\discovery\module\course_results\Course
     */
    private $course_results;

    /**
     *
     * @var multitype:\application\discovery\module\course_results\MarkMoment
     */
    private $mark_moments;

    function __construct(Application $application, ModuleInstance $module_instance)
    {
        parent :: __construct($application, $module_instance);
    }

    function get_data_manager()
    {
        return DataManager :: get_instance($this->get_module_instance());
    }

    function get_module_parameters()
    {
        return new Parameters(Request :: get(self :: PARAM_PROGRAMME_ID));
    }

    /**
     *
     * @return multitype:\application\discovery\module\course_results\Course
     */
    function get_course_results()
    {
        if (! isset($this->course_results))
        {
            $this->course_results = $this->get_data_manager()->retrieve_course_results(
                    $this->get_course_results_parameters());
        }
        return $this->course_results;
    }

    /**
     *
     * @return multitype:\application\discovery\module\course_results\MarkMoment
     */
    function get_mark_moments()
    {
        if (! isset($this->mark_moments))
        {
            $this->mark_moments = $this->get_data_manager()->retrieve_mark_moments(
                    $this->get_course_results_parameters());
        }
        return $this->mark_moments;
    }

    /**
     *
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
     *
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
    
    /*
     * (non-PHPdoc) @see application\discovery.Module::render()
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
        return ModuleInstance :: TYPE_DETAILS;
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