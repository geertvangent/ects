<?php
namespace Chamilo\Application\Discovery\Module\GroupUser;

use Chamilo\Libraries\File\Path;
use Chamilo\Libraries\File\Filesystem;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Application\Discovery\Instance\DataClass\Instance;
use Chamilo\Application\Discovery\Module\GroupUser\DataManager;

abstract class Module extends \Chamilo\Application\Discovery\Module
{
    const PARAM_GROUP_CLASS_ID = 'group_class_id';

    /**
     *
     * @var multitype:\application\discovery\module\group_user\GroupUser
     */
    private $group_user;

    public function get_data_manager()
    {
        return DataManager :: get_instance($this->get_module_instance());
    }

    public function get_module_parameters()
    {
        return new Parameters(Request :: get(self :: PARAM_GROUP_CLASS_ID));
    }

    /**
     *
     * @return multitype:\application\discovery\module\group_user\GroupUser
     */
    public function get_group_user()
    {
        if (! isset($this->group_user))
        {
            $this->group_user = $this->get_data_manager()->retrieve_group_users($this->get_module_parameters());
        }
        return $this->group_user;
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
