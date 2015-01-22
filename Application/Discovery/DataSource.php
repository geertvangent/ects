<?php
namespace Ehb\Application\Discovery;

use Chamilo\Libraries\File\Filesystem;
use Chamilo\Libraries\Storage\DataManager\Doctrine\Database;
use Chamilo\Libraries\Architecture\ClassnameUtilities;

class DataSource extends Database
{

    private $module_instance;

    /**
     * Constructor
     *
     * @param Instance $module_instance
     */
    public function __construct(\Ehb\Application\Discovery\Instance\DataClass\Instance $module_instance)
    {
        $this->module_instance = $module_instance;
        $this->initialize();
    }

    public function get_module_instance()
    {
        return $this->module_instance;
    }

    public function set_module_instance(\Ehb\Application\Discovery\Instance\DataClass\Instance $module_instance)
    {
        $this->module_instance = $module_instance;
    }

    public static function get_available_types()
    {
        $types = array();

        $data_sources = Filesystem :: get_directory_content(
            ClassnameUtilities :: getInstance()->namespaceToFullPath(__NAMESPACE__) . 'data_source/',
            Filesystem :: LIST_DIRECTORIES,
            false);

        $exceptions = array('php', 'resources');

        foreach ($data_sources as $data_source)
        {
            if (! in_array($data_source, $exceptions))
            {
                $types[] = __NAMESPACE__ . '\data_source\\' . $data_source;
            }
        }
        return $types;
    }
}
