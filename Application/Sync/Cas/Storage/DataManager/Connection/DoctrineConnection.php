<?php
namespace Ehb\Application\Sync\Cas\Storage\DataManager\Connection;

use Chamilo\Configuration\Configuration;
use Chamilo\Libraries\File\Path;
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
     * Use $config = Configuration :: getInstance(); to retrieve DB settings
     *
     * @param $connection Doctrine\DBAL\Connection
     */
    private function __construct($connection = null)
    {
        $classLoader = new ClassLoader('Doctrine', Path::getInstance()->getPluginPath());
        $classLoader->register();

        if (is_null($connection))
        {
            $cas_dbms = Configuration::getInstance()->get_setting(
                array(\Ehb\Application\Sync\Cas\Storage\Manager::context(), 'dbms'));
            $cas_user = Configuration::getInstance()->get_setting(
                array(\Ehb\Application\Sync\Cas\Storage\Manager::context(), 'user'));
            $cas_password = Configuration::getInstance()->get_setting(
                array(\Ehb\Application\Sync\Cas\Storage\Manager::context(), 'password'));
            $cas_host = Configuration::getInstance()->get_setting(
                array(\Ehb\Application\Sync\Cas\Storage\Manager::context(), 'host'));
            $cas_database = Configuration::getInstance()->get_setting(
                array(\Ehb\Application\Sync\Cas\Storage\Manager::context(), 'database'));

            $data_source_name = DataSourceName::factory(
                'Doctrine',
                array(
                    'driver' => $cas_dbms,
                    'username' => $cas_user,
                    'host' => $cas_host,
                    'name' => $cas_database,
                    'password' => $cas_password));

            $configuration = new \Doctrine\DBAL\Configuration();
            $connection_parameters = array(
                'dbname' => $data_source_name->get_database(),
                'user' => $data_source_name->get_username(),
                'password' => $data_source_name->get_password(),
                'host' => $data_source_name->get_host(),
                'driverClass' => $data_source_name->get_driver(true));
            $this->connection = DriverManager::getConnection($connection_parameters, $configuration);
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
    public static function getInstance()
    {
        if (! isset(self::$instance))
        {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function set_instance($connection)
    {
        self::$instance = new self($connection);
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
