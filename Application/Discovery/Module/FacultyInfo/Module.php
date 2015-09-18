<?php
namespace Ehb\Application\Discovery\Module\FacultyInfo;

use Ehb\Application\Discovery\Instance\DataClass\Instance;
use Chamilo\Libraries\File\Path;
use Chamilo\Libraries\File\Filesystem;
use Chamilo\Libraries\Platform\Session\Request;

abstract class Module extends \Ehb\Application\Discovery\Module
{
    const PARAM_FACULTY_ID = 'faculty_id';

    /**
     *
     * @var multitype:\application\discovery\module\faculty_info\Faculty
     */
    private $faculty;

    private $cache_trainings = array();

    public function get_module_parameters()
    {
        return self :: module_parameters();
    }

    public static function module_parameters()
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
    public function get_faculty()
    {
        if (! isset($this->faculty))
        {
            $this->faculty = DataManager :: get_instance($this->get_module_instance())->retrieve_faculty(
                $this->get_module_parameters());
        }
        return $this->faculty;
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
            $namespace = __NAMESPACE__ . '\Implementation\\' . $module;
            $types[] = $namespace;
        }

        return $types;
    }

    public function get_trainings_data($parameters)
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
