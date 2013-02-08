<?php
namespace application\ehb_sync\cas\data;

use common\libraries\DataSourceName;
use common\libraries\PlatformSetting;
use common\libraries\Path;
use Doctrine\DBAL\DriverManager;
use Doctrine\Common\ClassLoader;

/**
 * This class represents the current CAS Account database connection.
 *
 * @author Hans De Bisschop
 */
class DoctrineConnection extends \common\libraries\DoctrineConnection
{

    /**
     * Instance of this class for the singleton pattern.
     */
    private static $instance;

    /**
     *
     * @var Doctrine\DBAL\Connection
     */
    protected $connection;

    /**
     * Use $config = Configuration :: get_instance(); to retrieve DB settings
     *
     * @param $connection Doctrine\DBAL\Connection
     */
    private function __construct($connection = null)
    {
        $classLoader = new ClassLoader('Doctrine', Path :: get_plugin_path());
        $classLoader->register();

        if (is_null($connection))
        {
            $cas_dbms = PlatformSetting :: get('dbms', \application\ehb_sync\cas\data\Manager :: context());
            $cas_user = PlatformSetting :: get('user', \application\ehb_sync\cas\data\Manager :: context());
            $cas_password = PlatformSetting :: get('password', \application\ehb_sync\cas\data\Manager :: context());
            $cas_host = PlatformSetting :: get('host', \application\ehb_sync\cas\data\Manager :: context());
            $cas_database = PlatformSetting :: get('database', \application\ehb_sync\cas\data\Manager :: context());

            $data_source_name = DataSourceName :: factory('doctrine', $cas_dbms, $cas_user, $cas_host, $cas_database,
                    $cas_password);

            $configuration = new \Doctrine\DBAL\Configuration();
            $connection_parameters = array('dbname' => $data_source_name->get_database(),
                    'user' => $data_source_name->get_username(), 'password' => $data_source_name->get_password(),
                    'host' => $data_source_name->get_host(), 'driver' => $data_source_name->get_driver(true));
            $this->connection = DriverManager :: getConnection($connection_parameters, $configuration);
        }
        else
        {
            $this->connection = $connection;
        }
    }

    /**
     * Returns the instance of this class.
     *
     * @return Connection The instance.
     */
    static function get_instance()
    {
        if (! isset(self :: $instance))
        {
            self :: $instance = new self();
        }
        return self :: $instance;
    }

    static function set_instance($connection)
    {
        self :: $instance = new self($connection);
    }

    /**
     * Gets the database connection.
     *
     * @return Doctrine\DBAL\Connection
     */
    function get_connection()
    {
        return $this->connection;
    }

    /**
     *
     * @param $connection Doctrine\DBAL\Connection
     */
    function set_connection($connection)
    {
        $this->connection = $connection;
    }

    function set_option($option, $value)
    {
        $this->connection->setOption($option, $value);
    }
}
