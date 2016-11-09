<?php
namespace Ehb\Application\Discovery\Module\Profile;

use Chamilo\Libraries\File\Filesystem;
use Chamilo\Libraries\File\Path;
use Chamilo\Libraries\Platform\Session\Request;
use Ehb\Application\Discovery\Instance\Storage\DataClass\Instance;
use Ehb\Application\Discovery\Module\Profile\DataManager;

class Module extends \Ehb\Application\Discovery\Module
{

    /**
     *
     * @var \application\discovery\module\profile\Profile
     */
    private $profile;
    const PARAM_USER_ID = 'user_id';

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
     * @return \application\discovery\module\profile\Profile
     */
    public function get_profile()
    {
        if (! isset($this->profile))
        {
            $this->profile = DataManager :: getInstance($this->get_module_instance())->retrieve_profile(
                $this->get_module_parameters());
        }

        return $this->profile;
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

    public function get_type()
    {
        return Instance :: TYPE_USER;
    }
}
