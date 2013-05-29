<?php
namespace application\discovery\data_source\doctrine;

use Doctrine\DBAL\DriverManager;
use Doctrine\Common\ClassLoader;
use common\libraries\DataSourceName;
use common\libraries\DoctrineConnection;
use common\libraries\Path;

class Connection extends DoctrineConnection
{

    /**
     * Instance of this class for the singleton pattern.
     */
    private static $instance;

    private $data_source_instance;

    /**
     * The MDB2 Connection object.
     */
    protected $connection;

    /**
     * Constructor.
     */
    private function __construct($data_source_instance_id)
    {
        $classLoader = new ClassLoader('Doctrine', Path :: get_plugin_path());
        $classLoader->register();
        
        $this->data_source_instance = \application\discovery\data_source\DataManager :: retrieve_by_id(
            \application\discovery\data_source\Instance :: class_name(), 
            (int) $data_source_instance_id);
        
        $driver = $this->data_source_instance->get_setting('driver');
        $host = $this->data_source_instance->get_setting('host');
        $username = $this->data_source_instance->get_setting('username');
        $password = $this->data_source_instance->get_setting('password');
        $database = $this->data_source_instance->get_setting('database');
        
        $data_source_name = DataSourceName :: factory('Doctrine', $driver, $username, $host, $database, $password);
        
        $configuration = new \Doctrine\DBAL\Configuration();
        $connection_parameters = array(
            'dbname' => $data_source_name->get_database(), 
            'user' => $data_source_name->get_username(), 
            'password' => $data_source_name->get_password(), 
            'host' => $data_source_name->get_host(), 
            'driver' => $data_source_name->get_driver(true));
        $this->connection = DriverManager :: getConnection($connection_parameters, $configuration);
    }

    /**
     * Returns the instance of this class.
     * 
     * @return Connection The instance.
     */
    public static function get_instance($data_source_instance_id)
    {
        if (! isset(self :: $instance) || ! isset(self :: $instance[$data_source_instance_id]))
        {
            self :: $instance[$data_source_instance_id] = new self($data_source_instance_id);
        }
        return self :: $instance[$data_source_instance_id];
    }

    public function get_connection()
    {
        return $this->connection;
    }

    public function get_data_source_instance()
    {
        return $this->data_source_instance;
    }
}
