<?php
namespace application\discovery\module\training;

use common\libraries\DynamicVisualTab;
use common\libraries\DynamicVisualTabsRenderer;
use common\libraries\Breadcrumb;
use common\libraries\BreadcrumbTrail;
use common\libraries\Utilities;
use common\libraries\Filesystem;
use common\libraries\Request;
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
     * @var multitype:\application\discovery\module\training\Faculty
     */
    private $trainings;

    private $cache_trainings = array();

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
     * @return multitype:\application\discovery\module\training\Faculty
     */
    function get_trainings($year)
    {
        if (! isset($this->trainings[$year]))
        {
            $this->trainings[$year] = DataManager :: get_instance($this->get_module_instance())->retrieve_trainings(
                    $year);
        }
        return $this->trainings[$year];
    }

    function get_trainings_data($year)
    {
        if (! isset($this->cache_trainings[$year]))
        {
            foreach ($this->get_trainings($year) as $training)
            {
                $this->cache_trainings[$year][] = $training;
            }
        }
        return $this->cache_trainings[$year];
    }

    function has_trainings($year)
    {
        return count($this->get_trainings_data($year)) > 0;
    }

    function get_trainings_table($year)
    {
        $trainings = $this->get_trainings_data($year);
        
        $data = array();
        
        foreach ($trainings as $key => $training)
        {
            $row = array();
            
            $row[] = $training->get_name();
            $row[] = $training->get_start_date();
            $row[] = $training->get_end_date();
            $data[] = $row;
        }
        
        $table = new SortableTable($data);
        
        $table->set_header(0, Translation :: get('Name'), false);
        $table->set_header(1, Translation :: get('StartDate'), false);
        $table->set_header(2, Translation :: get('EndDate'), false);
        
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
     * (non-PHPdoc) @see application\discovery\module\training\Module::render()
     */
    function render()
    {
        BreadcrumbTrail :: get_instance()->add(
                new Breadcrumb(null, Translation :: get('TypeName', null, Utilities :: get_namespace_from_object($this))));
        
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
        
        $tabs = new DynamicVisualTabsRenderer('training_list', $this->get_trainings_table($current_year)->as_html());
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