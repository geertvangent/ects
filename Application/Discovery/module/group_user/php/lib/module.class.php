<?php
namespace application\discovery\module\group_user;

use common\libraries\Path;
use common\libraries\Filesystem;
use common\libraries\Request;
use application\discovery\ModuleInstance;
use application\discovery\module\group_user\DataManager;

abstract class Module extends \application\discovery\Module
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
        return ModuleInstance :: TYPE_DETAILS;
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
