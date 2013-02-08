<?php
namespace application\discovery\module\training_info;

use common\libraries\Filesystem;
use common\libraries\Request;
use common\libraries\Path;
use application\discovery\ModuleInstance;

abstract class Module extends \application\discovery\Module
{
    const PARAM_TRAINING_ID = 'training_id';

    /**
     *
     * @var multitype:\application\discovery\module\training_info\Faculty
     */
    private $training;

    function get_module_parameters()
    {
        return self :: module_parameters();
    }

    static function module_parameters()
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
                    $this->get_module_parameters());
        }
        return $this->training;
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
