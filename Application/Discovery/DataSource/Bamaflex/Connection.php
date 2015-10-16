<?php
namespace Ehb\Application\Discovery\DataSource\Bamaflex;

use Chamilo\Libraries\File\Path;
use Chamilo\Libraries\Storage\DataManager\Doctrine\DataSourceName;
use Doctrine\Common\ClassLoader;
use Doctrine\DBAL\DriverManager;

class Connection extends \Chamilo\Libraries\Storage\DataManager\Doctrine\Connection
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
        $classLoader = new ClassLoader('Doctrine', Path :: getInstance()->getPluginPath());
        $classLoader->register();

        $this->data_source_instance = \Ehb\Application\Discovery\DataSource\Storage\DataManager :: retrieve_by_id(
            \Ehb\Application\Discovery\DataSource\Storage\DataClass\Instance :: class_name(),
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
            'driverClass' => $data_source_name->get_driver(true));


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

    public function set_option($option, $value)
    {
        $this->connection->setOption($option, $value);
    }

    public function get_data_source_instance()
    {
        return $this->data_source_instance;
    }
}
