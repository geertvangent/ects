<?php
namespace application\discovery\module\faculty_info;

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
    const PARAM_FACULTY_ID = 'faculty_id';
    /**
     * @var multitype:\application\discovery\module\faculty_info\Faculty
     */
    private $faculty;
    private $cache_trainings = array();
    private $cache_faculty = array();

    function __construct(Application $application, ModuleInstance $module_instance)
    {
        parent :: __construct($application, $module_instance);
    }

    function get_faculty_parameters()
    {
        return self :: get_module_parameters();
    }

    static function get_module_parameters()
    {
        $faculty = Request :: get(self :: PARAM_FACULTY_ID);
        
        $parameter = new Parameters();
        if ($faculty)
        {
            $parameter->set_faculty_id($faculty);
        }
        return $parameter;
    
    }

    /**
     * @return multitype:\application\discovery\module\faculty_info\Faculty
     */
    function get_faculty()
    {
        if (! isset($this->faculty))
        {
            $this->faculty = DataManager :: get_instance($this->get_module_instance())->retrieve_faculty($this->get_faculty_parameters());
        }
        return $this->faculty;
    }
    
    /* (non-PHPdoc)
     * @see application\discovery\module\faculty_info\Module::render()
     */
    function render()
    {
        $html = array();
        
        return implode("\n", $html);
    }

    function get_type()
    {
        return ModuleInstance :: TYPE_DETAILS;
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

    function get_trainings_data($parameters)
    {
        $faculty_id = $parameters->get_faculty_id();
        $source = $parameters->get_source();
        
        if (! isset($this->cache_trainings[$source][$faculty_id]))
        {
            $this->cache_trainings[$source][$faculty_id] = DataManager :: get_instance($this->get_module_instance())->retrieve_trainings($parameters);
        }
        return $this->cache_trainings[$source][$faculty_id];
    }

}
?>