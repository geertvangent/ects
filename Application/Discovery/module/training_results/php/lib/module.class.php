<?php
namespace application\discovery\module\training_results;

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
    const PARAM_TRAINING_ID = 'training_id';

    /**
     *
     * @var multitype:\application\discovery\module\training_results\Course
     */
    private $training_results;

    function __construct(Application $application, ModuleInstance $module_instance)
    {
        parent :: __construct($application, $module_instance);
    }

    function get_data_manager()
    {
        return DataManager :: get_instance($this->get_module_instance());
    }

    function get_training_results_parameters()
    {
        return new Parameters(Request :: get(self :: PARAM_TRAINING_ID));
    }

    /**
     *
     * @return multitype:\application\discovery\module\training_results\Course
     */
    function get_training_results()
    {
        if (! isset($this->training_results))
        {
            $this->training_results = $this->get_data_manager()->retrieve_training_results(
                    $this->get_training_results_parameters());
        }
        return $this->training_results;
    }

    /**
     *
     * @return multitype:multitype:string
     */
    function get_table_data()
    {
        $data = array();
        
        return $data;
    }

    /**
     *
     * @return multitype:string
     */
    function get_table_headers()
    {
        $headers = array();
        
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