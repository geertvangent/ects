<?php
namespace application\discovery\module\course;

use common\libraries\Path;
use common\libraries\Filesystem;
use common\libraries\Request;
use application\discovery\ModuleInstance;
use application\discovery\module\course\DataManager;

abstract class Module extends \application\discovery\Module
{
    const PARAM_PROGRAMME_ID = 'programme_id';

    /**
     *
     * @var \application\discovery\module\course\Course
     */
    private $course;

    function get_data_manager()
    {
        return DataManager :: get_instance($this->get_module_instance());
    }

    function get_module_parameters()
    {
        return new Parameters(Request :: get(self :: PARAM_PROGRAMME_ID));
    }

    /**
     *
     * @return \application\discovery\module\course\Course
     */
    function get_course()
    {
        if (! isset($this->course))
        {
            $this->course = $this->get_data_manager()->retrieve_course($this->get_module_parameters());
        }
        return $this->course;
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
