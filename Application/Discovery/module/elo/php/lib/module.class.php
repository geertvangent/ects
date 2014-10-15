<?php
namespace application\discovery\module\elo;

use application\discovery\instance\Instance;
use libraries\file\Path;
use libraries\file\Filesystem;

abstract class Module extends \application\discovery\Module
{

    public function get_type()
    {
        return Instance :: TYPE_INFORMATION;
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
