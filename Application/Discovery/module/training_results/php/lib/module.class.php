<?php
namespace application\discovery\module\training_results;

use common\libraries\Path;
use common\libraries\Filesystem;
use common\libraries\Request;
use application\discovery\ModuleInstance;
use application\discovery\module\training_results\DataManager;

class Module extends \application\discovery\Module
{
    const PARAM_TRAINING_ID = 'training_id';

    /**
     *
     * @var multitype:\application\discovery\module\training_results\Course
     */
    private $training_results;

    function get_data_manager()
    {
        return DataManager :: get_instance($this->get_module_instance());
    }

    function get_module_parameters()
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
                    $this->get_module_parameters());
        }
        return $this->training_results;
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