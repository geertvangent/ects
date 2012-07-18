<?php
namespace application\discovery\module\cas;

use common\libraries\ToolbarItem;

use common\libraries\Path;
use common\libraries\Filesystem;
use common\libraries\Request;
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
    private $cas_statistics;
    private $applications;
    
    const PARAM_USER_ID = 'user_id';

    function __construct(Application $application, ModuleInstance $module_instance)
    {
        parent :: __construct($application, $module_instance);
    }

    function get_cas_parameters()
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

    function get_cas_statistics()
    {
        if (! isset($this->cas_statistics))
        {
            $path = Path :: get_cache_path(__NAMESPACE__) . 'cas_statistics/' . md5(serialize($this->get_cas_parameters()));
            
            if (! file_exists($path))
            {
                $this->cas_statistics = DataManager :: get_instance($this->get_module_instance())->retrieve_cas_statistics($this->get_cas_parameters());
                Filesystem :: write_to_file($path, serialize($this->cas_statistics));
            }
            else
            {
                $this->cas_statistics = unserialize(file_get_contents($path));
            }
        }
        return $this->cas_statistics;
    }

    function get_applications()
    {
        if (! isset($this->applications))
        {
            $this->applications = DataManager :: get_instance($this->get_module_instance())->retrieve_applications();
        }
        return $this->applications;
    }

    function has_data($parameters = null)
    {
        $parameters = $parameters ? $parameters : $this->get_cas_parameters();
        return $this->get_data_manager()->count_cas_statistics($parameters);
    }
    
    /* (non-PHPdoc)
     * @see application\discovery.Module::render()
     */
    function render()
    {
        $html = array();
        
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