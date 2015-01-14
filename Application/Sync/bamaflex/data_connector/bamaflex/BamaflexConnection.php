<?php
namespace Chamilo\Application\EhbSync\Bamaflex\DataConnector\Bamaflex;

use Chamilo\Libraries\Storage\DataSourceName;
use Chamilo\Libraries\Platform\PlatformSetting;
use Chamilo\Doctrine\DBAL\DriverManager;
use Chamilo\Doctrine\Common\ClassLoader;
use Chamilo\Libraries\File\Path;

/**
 * This class represents the current CAS Account database connection.
 *
 * @author Hans De Bisschop
 */
class BamaflexConnection extends \Chamilo\Libraries\Storage\DoctrineConnection
{

    /**
     * Instance of this class for the singleton pattern.
     */
    private static $instance;

    /**
     * The MDB2 Connection object.
     */
    protected $connection;

    /**
     * Constructor.
     */
    private function __construct()
    {
        $dbms = PlatformSetting :: get('dbms', Manager :: context());
        $user = PlatformSetting :: get('user', Manager :: context());
        $password = PlatformSetting :: get('password', Manager :: context());
        $host = PlatformSetting :: get('host', Manager :: context());
        $database = PlatformSetting :: get('database', Manager :: context());

        $classLoader = new ClassLoader('Doctrine', Path :: get_plugin_path());
        $classLoader->register();

        $data_source_name = DataSourceName :: factory('doctrine', $dbms, $user, $host, $database, $password);
        $configuration = new \Chamilo\Doctrine\DBAL\Configuration();
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
    public static function get_instance()
    {
        if (! isset(self :: $instance))
        {
            self :: $instance = new self();
        }
        return self :: $instance;
    }

    /**
     * Gets the database connection.
     *
     * @return mixed MDB2 DB Conenction.
     */
    public function get_connection()
    {
        return $this->connection;
    }

    public function set_option($option, $value)
    {
        $this->connection->setOption($option, $value);
    }
}
