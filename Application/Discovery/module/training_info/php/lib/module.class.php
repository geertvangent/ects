<?php
namespace application\discovery\module\training_info;

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
use application\discovery\module\profile\DataManager;

class Module extends \application\discovery\Module
{
    const PARAM_TRAINING_ID = 'training_id';

    /**
     *
     * @var multitype:\application\discovery\module\training_info\Faculty
     */
    private $training;

    private $cache_training = array();

    function __construct(Application $application, ModuleInstance $module_instance)
    {
        parent :: __construct($application, $module_instance);
    }

    function get_training_parameters()
    {
        return self :: get_module_parameters();
    }

    static function get_module_parameters()
    {
        $training = Request :: get(self :: PARAM_TRAINING_ID);
        
        $parameter = new Parameters();
        if ($training)
        {
            $parameter->set_training_id($training);
        }
        return $parameter;
    }

    /**
     *
     * @return multitype:\application\discovery\module\training_info\Faculty
     */
    function get_training()
    {
        if (! isset($this->training))
        {
            $this->training = DataManager :: get_instance($this->get_module_instance())->retrieve_training(
                    $this->get_training_parameters());
        }
        return $this->training;
    }
    
    /*
     * (non-PHPdoc) @see application\discovery\module\training_info\Module::render()
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