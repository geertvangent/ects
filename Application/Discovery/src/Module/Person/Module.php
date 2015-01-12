<?php
namespace Chamilo\Application\Discovery\Module\Person;

use Chamilo\Application\Discovery\Instance\Instance;
use Chamilo\Libraries\File\Path;
use Chamilo\Libraries\File\Filesystem;

abstract class Module extends \Chamilo\Application\Discovery\Module
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
