<?php
namespace application\discovery\module\student_year;

use common\libraries\Path;
use common\libraries\Filesystem;
use common\libraries\Request;
use common\libraries\Application;
use application\discovery\ModuleInstance;
use application\discovery\module\profile\DataManager;

abstract class Module extends \application\discovery\Module
{

    /**
     *
     * @var multitype:\application\discovery\module\student_year\StudentYear
     */
    private $student_years;
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

    /**
     *
     * @return multitype:\application\discovery\module\student_year\StudentYear
     */
    function get_student_years()
    {
        if (! isset($this->student_years))
        {
            $this->student_years = DataManager :: get_instance($this->get_module_instance())->retrieve_student_years(
                    $this->get_module_parameters());
        }
        return $this->student_years;
    }

    function has_data($parameters = null)
    {
        $parameters = $parameters ? $parameters : $this->get_module_parameters();
        return $this->get_data_manager()->count_student_years($parameters);
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

    function get_type()
    {
        return ModuleInstance :: TYPE_USER;
    }
}
