<?php
namespace application\discovery\module\course_results;

use common\libraries\Path;
use common\libraries\Filesystem;
use common\libraries\Request;
use application\discovery\ModuleInstance;
use application\discovery\module\course_results\DataManager;

abstract class Module extends \application\discovery\Module
{
    const PARAM_PROGRAMME_ID = 'programme_id';

    /**
     *
     * @var multitype:\application\discovery\module\course_results\Course
     */
    private $course_results;

    /**
     *
     * @var multitype:\application\discovery\module\course_results\MarkMoment
     */
    private $mark_moments;

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
     * @return multitype:\application\discovery\module\course_results\Course
     */
    function get_course_results()
    {
        if (! isset($this->course_results))
        {
            $this->course_results = $this->get_data_manager()->retrieve_course_results($this->get_module_parameters());
        }
        return $this->course_results;
    }

    /**
     *
     * @return multitype:\application\discovery\module\course_results\MarkMoment
     */
    function get_mark_moments()
    {
        if (! isset($this->mark_moments))
        {
            $this->mark_moments = $this->get_data_manager()->retrieve_mark_moments($this->get_module_parameters());
        }
        return $this->mark_moments;
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
