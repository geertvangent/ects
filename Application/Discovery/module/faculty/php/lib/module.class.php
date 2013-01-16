<?php
namespace application\discovery\module\faculty;

use common\libraries\Request;
use common\libraries\DynamicVisualTabsRenderer;
use common\libraries\DynamicVisualTab;
use common\libraries\Filesystem;
use common\libraries\DynamicContentTab;
use common\libraries\DynamicTabsRenderer;
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
    const PARAM_YEAR = 'year';

    /**
     *
     * @var multitype:\application\discovery\module\faculty\Faculty
     */
    private $faculties;

    private $cache_faculties = array();

    private $years;

    function __construct(Application $application, ModuleInstance $module_instance)
    {
        parent :: __construct($application, $module_instance);
    }

    static function module_parameters()
    {
        $year = Request :: get(self :: PARAM_YEAR);
        
        $parameter = new Parameters();
        if ($year)
        {
            $parameter->set_year($year);
        }
        
        return $parameter;
    }

    /**
     *
     * @return multitype:\application\discovery\module\faculty\Faculty
     */
    function get_faculties($year)
    {
        if (! isset($this->faculties[$year]))
        {
            $this->faculties[$year] = DataManager :: get_instance($this->get_module_instance())->retrieve_faculties(
                    $year);
        }
        return $this->faculties[$year];
    }

    function get_faculties_data($year)
    {
        if (! isset($this->cache_faculties[$year]))
        {
            foreach ($this->get_faculties($year) as $faculty)
            {
                $this->cache_faculties[$year][] = $faculty;
            }
        }
        return $this->cache_faculties[$year];
    }

    function has_faculties($year)
    {
        return count($this->get_faculties_data($year)) > 0;
    }

    function get_faculties_table($year)
    {
        $faculties = $this->get_faculties_data($year);
        
        $data = array();
        
        foreach ($faculties as $key => $faculty)
        {
            $row = array();
            
            $row[] = $faculty->get_name();
            $data[] = $row;
        }
        
        $table = new SortableTable($data);
        $table->set_header(0, Translation :: get('Name'), false);
        
        return $table;
    }

    function get_years()
    {
        if (! isset($this->years))
        {
            $this->years = DataManager :: get_instance($this->get_module_instance())->retrieve_years();
        }
        return $this->years;
    }
    
    /*
     * (non-PHPdoc) @see application\discovery\module\faculty\Module::render()
     */
    function render()
    {
        $html = array();
        if (is_null(self :: module_parameters()->get_year()))
        {
            $years = $this->get_years();
            $current_year = $years[0];
        }
        else
        {
            $current_year = self :: module_parameters()->get_year();
        }
        
        $tabs = new DynamicVisualTabsRenderer('faculty_list', $this->get_faculties_table($current_year)->as_html());
        
        foreach ($this->get_years() as $year)
        {
            $parameters = self :: module_parameters();
            $parameters->set_year($year);
            $tabs->add_tab(
                    new DynamicVisualTab($year, $year, null, 
                            $this->get_instance_url($this->get_module_instance()->get_id(), $parameters), 
                            $current_year == $year));
        }
        
        $html[] = $tabs->render();
        
        return implode("\n", $html);
    }

    function get_type()
    {
        return ModuleInstance :: TYPE_INFORMATION;
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