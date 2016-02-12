<?php
namespace Ehb\Application\Sync\Cas\Data\DataManager\Connection;

use Chamilo\Libraries\File\Path;
use Chamilo\Libraries\Platform\Configuration\PlatformSetting;
use Chamilo\Libraries\Storage\DataManager\DataSourceName;
use Doctrine\Common\ClassLoader;
use Doctrine\DBAL\DriverManager;

/**
 * This class represents the current CAS Account database connection.
 * 
 * @author Hans De Bisschop
 */
class DoctrineConnection extends \Chamilo\Libraries\Storage\DataManager\Doctrine\Connection
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
        $classLoader = new ClassLoader('Doctrine', Path :: getInstance()->getPluginPath());
        $classLoader->register();
        
        if (is_null($connection))
        {
            $cas_dbms = PlatformSetting :: get('dbms', \Ehb\Application\Sync\Cas\Data\Manager :: context());
            $cas_user = PlatformSetting :: get('user', \Ehb\Application\Sync\Cas\Data\Manager :: context());
            $cas_password = PlatformSetting :: get('password', \Ehb\Application\Sync\Cas\Data\Manager :: context());
            $cas_host = PlatformSetting :: get('host', \Ehb\Application\Sync\Cas\Data\Manager :: context());
            $cas_database = PlatformSetting :: get('database', \Ehb\Application\Sync\Cas\Data\Manager :: context());
            
            $data_source_name = DataSourceName :: factory(
                'doctrine', 
                $cas_dbms, 
                $cas_user, 
                $cas_host, 
                $cas_database, 
                $cas_password);
            
            $configuration = new \Doctrine\DBAL\Configuration();
            $connection_parameters = array(
                'dbname' => $data_source_name->get_database(), 
                'user' => $data_source_name->get_username(), 
                'password' => $data_source_name->get_password(), 
                'host' => $data_source_name->get_host(), 
                'driverClass' => $data_source_name->get_driver(true));
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
    public static function get_instance()
    {
        if (! isset(self :: $instance))
        {
            self :: $instance = new self();
        }
        return self :: $instance;
    }

    public static function set_instance($connection)
    {
        self :: $instance = new self($connection);
    }

    /**
     * Gets the database connection.
     * 
     * @return Doctrine\DBAL\Connection
     */
    public function get_connection()
    {
        return $this->connection;
    }

    /**
     *
     * @param $connection Doctrine\DBAL\Connection
     */
    public function set_connection($connection)
    {
        $this->connection = $connection;
    }

    public function set_option($option, $value)
    {
        $this->connection->setOption($option, $value);
    }
}
