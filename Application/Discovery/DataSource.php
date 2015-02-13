<?php
namespace Ehb\Application\Discovery;

use Chamilo\Libraries\File\Filesystem;
use Chamilo\Libraries\Storage\DataManager\Doctrine\Database;
use Chamilo\Libraries\File\Path;

class DataSource extends Database
{

    private $module_instance;

    /**
     * Constructor
     *
     * @param Instance $module_instance
     */
    public function __construct(\Ehb\Application\Discovery\Instance\DataClass\Instance $module_instance,
        $connection = null)
    {
        $this->module_instance = $module_instance;
        parent :: __construct($connection);
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
            Path :: getInstance()->namespaceToFullPath(__NAMESPACE__) . 'DataSource/',
            Filesystem :: LIST_DIRECTORIES,
            false);

        $exceptions = array('resources');

        foreach ($data_sources as $data_source)
        {
            if (! in_array($data_source, $exceptions))
            {
                $types[] = __NAMESPACE__ . '\DataSource\\' . $data_source;
            }
        }
        return $types;
    }
}
