<?php
namespace Ehb\Application\Discovery\Module\Person;

use Chamilo\Libraries\File\Filesystem;
use Chamilo\Libraries\File\Path;
use Ehb\Application\Discovery\Instance\Storage\DataClass\Instance;

abstract class Module extends \Ehb\Application\Discovery\Module
{

    public function get_type()
    {
        return Instance :: TYPE_INFORMATION;
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
}
