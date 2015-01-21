<?php
namespace Chamilo\Application\Discovery\Module\Course;

use Chamilo\Libraries\File\Filesystem;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Application\Discovery\Instance\DataClass\Instance;
use Chamilo\Application\Discovery\Module\Course\DataManager;

abstract class Module extends \Chamilo\Application\Discovery\Module
{
    const PARAM_PROGRAMME_ID = 'programme_id';

    /**
     *
     * @var \application\discovery\module\course\Course
     */
    private $course;

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
     * @return \application\discovery\module\course\Course
     */
    public function get_course()
    {
        if (! isset($this->course))
        {
            $this->course = $this->get_data_manager()->retrieve_course($this->get_module_parameters());
        }
        return $this->course;
    }

    public function get_type()
    {
        return Instance :: TYPE_DETAILS;
    }

    public static function get_available_implementations()
    {
        $types = array();

        $modules = Filesystem :: get_directory_content(
            ClassnameUtilities :: getInstance()->namespaceToFullPath(__NAMESPACE__) . 'implementation/', 
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
