<?php
namespace application\discovery;

use libraries\storage\DoctrineDatabase;
use libraries\file\Filesystem;
use libraries\file\Path;

class DataSource extends DoctrineDatabase
{

    private $module_instance;

    /**
     * Constructor
     * 
     * @param Instance $module_instance
     */
    public function __construct(\application\discovery\instance\Instance $module_instance)
    {
        $this->module_instance = $module_instance;
        $this->initialize();
    }

    public function get_module_instance()
    {
        return $this->module_instance;
    }

    public function set_module_instance(\application\discovery\instance\Instance $module_instance)
    {
        $this->module_instance = $module_instance;
    }

    public static function get_available_types()
    {
        $types = array();
        
        $data_sources = Filesystem :: get_directory_content(
            Path :: namespace_to_full_path(__NAMESPACE__) . 'data_source/', 
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
