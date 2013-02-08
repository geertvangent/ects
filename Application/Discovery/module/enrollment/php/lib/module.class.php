<?php
namespace application\discovery\module\enrollment;

use common\libraries\Filesystem;
use common\libraries\Request;
use common\libraries\Path;
use common\libraries\Application;
use application\discovery\ModuleInstance;

abstract class Module extends \application\discovery\Module
{

    /**
     *
     * @var multitype:\application\discovery\module\enrollment\Enrollment
     */
    private $enrollments;
    const PARAM_USER_ID = 'user_id';

    function __construct(Application $application, ModuleInstance $module_instance)
    {
        parent :: __construct($application, $module_instance);
    }

    function get_module_parameters()
    {
        $parameter = self :: module_parameters();
        if (! $parameter->get_user_id())
        {
            $parameter->set_user_id($this->get_application()->get_user_id());
        }
        return $parameter;
    }

    static function module_parameters()
    {
        $param_user = Request :: get(self :: PARAM_USER_ID);
        $parameter = new Parameters();
        if ($param_user)
        {
            $parameter->set_user_id($param_user);
        }
        return $parameter;
    }

    /**
     *
     * @return multitype:\application\discovery\module\enrollment\Enrollment
     */
    function get_enrollments()
    {
        if (! isset($this->enrollments))
        {
            $this->enrollments = DataManager :: get_instance($this->get_module_instance())->retrieve_enrollments(
                    $this->get_module_parameters());
        }
        return $this->enrollments;
    }

    function has_data($parameters = null)
    {
        $parameters = $parameters ? $parameters : $this->get_module_parameters();
        return $this->get_data_manager()->count_enrollments($parameters);
    }

    function get_type()
    {
        return ModuleInstance :: TYPE_USER;
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
