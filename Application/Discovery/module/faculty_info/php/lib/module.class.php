<?php
namespace application\discovery\module\faculty_info;

use common\libraries\Request;
use common\libraries\Filesystem;
use common\libraries\Path;
use common\libraries\Application;
use application\discovery\ModuleInstance;

abstract class Module extends \application\discovery\Module
{
    const PARAM_FACULTY_ID = 'faculty_id';

    /**
     *
     * @var multitype:\application\discovery\module\faculty_info\Faculty
     */
    private $faculty;

    private $cache_trainings = array();

    function get_module_parameters()
    {
        return self :: module_parameters();
    }

    static function module_parameters()
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
     *
     * @return multitype:\application\discovery\module\faculty_info\Faculty
     */
    function get_faculty()
    {
        if (! isset($this->faculty))
        {
            $this->faculty = DataManager :: get_instance($this->get_module_instance())->retrieve_faculty(
                    $this->get_module_parameters());
        }
        return $this->faculty;
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

    function get_trainings_data($parameters)
    {
        $faculty_id = $parameters->get_faculty_id();
        $source = $parameters->get_source();

        if (! isset($this->cache_trainings[$source][$faculty_id]))
        {
            $this->cache_trainings[$source][$faculty_id] = DataManager :: get_instance($this->get_module_instance())->retrieve_trainings(
                    $parameters);
        }
        return $this->cache_trainings[$source][$faculty_id];
    }
}
