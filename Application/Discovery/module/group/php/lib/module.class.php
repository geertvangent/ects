<?php
namespace application\discovery\module\group;

use common\libraries\Filesystem;
use common\libraries\Request;
use common\libraries\Path;
use application\discovery\ModuleInstance;
use application\discovery\module\profile\DataManager;

abstract class Module extends \application\discovery\Module
{
    const PARAM_TRAINING_ID = 'training_id';

    /**
     *
     * @var multitype:\application\discovery\module\group\Group
     */
    private $groups;

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
     * @return multitype:\application\discovery\module\group\Group
     */
    function get_groups()
    {
        if (! isset($this->groups))
        {
            $this->groups = DataManager :: get_instance($this->get_module_instance())->retrieve_groups(
                    $this->get_module_parameters());
        }
        return $this->groups;
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