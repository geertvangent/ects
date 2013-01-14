<?php
namespace application\discovery\module\profile;

use common\libraries\Path;
use common\libraries\Filesystem;
use common\libraries\Request;
use common\libraries\Application;
use application\discovery\ModuleInstance;
use application\discovery\module\profile\DataManager;

class Module extends \application\discovery\Module
{

    /**
     *
     * @var \application\discovery\module\profile\Profile
     */
    private $profile;
    const PARAM_USER_ID = 'user_id';

    function __construct(Application $application, ModuleInstance $module_instance)
    {
        parent :: __construct($application, $module_instance);
    }

    function get_profile_parameters()
    {
        $parameter = self :: get_module_parameters();
        if (! $parameter->get_user_id())
        {
            $parameter->set_user_id($this->get_application()->get_user_id());
        }
        return $parameter;
    }

    static function get_module_parameters()
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
     * @return \application\discovery\module\profile\Profile
     */
    function get_profile()
    {
        if (! isset($this->profile))
        {

            $this->profile = DataManager :: get_instance($this->get_module_instance())->retrieve_profile(
                    $this->get_profile_parameters());
        }

        return $this->profile;
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
?>