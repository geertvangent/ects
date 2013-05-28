<?php
namespace application\discovery\module\advice;

use common\libraries\Filesystem;
use common\libraries\Request;
use common\libraries\Path;
use common\libraries\Application;
use application\discovery\instance\Instance;
use application\discovery\module\profile\DataManager;

abstract class Module extends \application\discovery\Module
{
    const PARAM_USER_ID = 'user_id';

    /**
     *
     * @var multitype:\application\discovery\module\advice\Advice
     */
    private $advices;

    public function __construct(Application $application, Instance $module_instance)
    {
        parent :: __construct($application, $module_instance);
    }

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
        $parameter = new Parameters();
        if ($param_user)
        {
            $parameter->set_user_id($param_user);
        }
        return $parameter;
    }

    /**
     *
     * @return multitype:\application\discovery\module\advice\TeachingAssignment
     */
    public function get_advices()
    {
        if (! isset($this->advices))
        {
            $this->advices = DataManager :: get_instance($this->get_module_instance())->retrieve_advices(
                $this->get_module_parameters());
        }
        return $this->advices;
    }

    public function has_data($parameters = null)
    {
        $parameters = $parameters ? $parameters : $this->get_module_parameters();
        return $this->get_data_manager()->count_advices($parameters);
    }

    public function get_type()
    {
        return Instance :: TYPE_USER;
    }

    public static function get_available_implementations()
    {
        $types = array();
        
        $modules = Filesystem :: get_directory_content(
            Path :: namespace_to_full_path(__NAMESPACE__) . 'implementation/', 
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
