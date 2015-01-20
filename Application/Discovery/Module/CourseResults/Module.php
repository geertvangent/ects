<?php
namespace Chamilo\Application\Discovery\Module\CourseResults;

use Chamilo\Libraries\File\Path;
use Chamilo\Libraries\File\Filesystem;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Application\Discovery\Instance\DataClass\Instance;
use Chamilo\Application\Discovery\Module\CourseResults\DataManager;

abstract class Module extends \Chamilo\Application\Discovery\Module
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

    public function get_data_manager()
    {
        return DataManager :: get_instance($this->get_module_instance());
    }

    public function get_module_parameters()
    {
        return new Parameters(Request :: get(self :: PARAM_PROGRAMME_ID));
    }

    /**
     *
     * @return multitype:\application\discovery\module\course_results\Course
     */
    public function get_course_results()
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
    public function get_mark_moments()
    {
        if (! isset($this->mark_moments))
        {
            $this->mark_moments = $this->get_data_manager()->retrieve_mark_moments($this->get_module_parameters());
        }
        return $this->mark_moments;
    }

    public function get_type()
    {
        return Instance :: TYPE_DETAILS;
    }

    public static function get_available_implementations()
    {
        $types = array();
        
        $modules = Filesystem :: get_directory_content(
            Path :: getInstance()->namespaceToFullPath(__NAMESPACE__) . 'implementation/', 
            Filesystem :: LIST_DIRECTORIES, 
            false);
        foreach ($modules as $module)
        {
            $namespace = __NAMESPACE__ . '\implementation\\' . $module;
            $types[] = $namespace;
        }
        return $types;
    }
}
