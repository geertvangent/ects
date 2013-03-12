<?php
namespace application\discovery\module\person;

use application\discovery\ModuleInstance;
use common\libraries\Path;
use common\libraries\Filesystem;

abstract class Module extends \application\discovery\Module
{

    public function get_type()
    {
        return ModuleInstance :: TYPE_INFORMATION;
    }

    public static function get_available_implementations()
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
