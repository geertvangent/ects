<?php
namespace Ehb\Application\Discovery\Module\TeachingAssignment;

use Chamilo\Libraries\File\Filesystem;
use Chamilo\Libraries\File\Path;
use Chamilo\Libraries\Platform\Session\Request;
use Ehb\Application\Discovery\Instance\Storage\DataClass\Instance;
use Ehb\Application\Discovery\Module\Profile\DataManager;

abstract class Module extends \Ehb\Application\Discovery\Module
{
    const PARAM_USER_ID = 'user_id';
    const PARAM_YEAR = 'year';

    /**
     *
     * @var multitype:\application\discovery\module\teaching_assignment\TeachingAssignment
     */
    private $teaching_assignments;

    public function get_module_parameters()
    {
        $parameter = self :: module_parameters();
        if (! $parameter->get_user_id())
        {
            $parameter->set_user_id($this->get_application()->get_user_id());
        }
        return $parameter;
    }

    public static function module_parameters()
    {
        $param_user = Request :: get(self :: PARAM_USER_ID);
        $year = Request :: get(self :: PARAM_YEAR);
        $parameter = new Parameters();
        if ($param_user)
        {
            $parameter->set_user_id($param_user);
        }

        if ($year)
        {
            $parameter->set_year($year);
        }
        return $parameter;
    }

    /**
     *
     * @return multitype:\application\discovery\module\teaching_assignment\TeachingAssignment
     */
    public function get_teaching_assignments($parameters)
    {
        $year = $parameters->get_year();
        $user_id = $parameters->get_user_id();

        if (! isset($this->teaching_assignments[$user_id][$year]))
        {
            $this->teaching_assignments[$user_id][$year] = DataManager :: getInstance($this->get_module_instance())->retrieve_teaching_assignments(
                $parameters);
        }
        return $this->teaching_assignments[$user_id][$year];
    }

    public function has_data($parameters = null)
    {
        $parameters = $parameters ? $parameters : $this->get_module_parameters();
        return $this->get_data_manager()->count_teaching_assignments($parameters);
    }

    public function get_type()
    {
        return Instance :: TYPE_USER;
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
}
