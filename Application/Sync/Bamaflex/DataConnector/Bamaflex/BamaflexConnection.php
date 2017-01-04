<?php
namespace Ehb\Application\Sync\Bamaflex\DataConnector\Bamaflex;

use Chamilo\Configuration\Configuration;
use Chamilo\Libraries\Storage\DataManager\DataSourceName;
use Doctrine\DBAL\DriverManager;

/**
 * This class represents the current CAS Account database connection.
 *
 * @author Hans De Bisschop
 */
class BamaflexConnection extends \Chamilo\Libraries\Storage\DataManager\Doctrine\Connection
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
        $dbms = Configuration::getInstance()->get_setting(array('Ehb\Application\Sync', 'dbms'));
        $user = Configuration::getInstance()->get_setting(array('Ehb\Application\Sync', 'user'));
        $password = Configuration::getInstance()->get_setting(array('Ehb\Application\Sync', 'password'));
        $host = Configuration::getInstance()->get_setting(array('Ehb\Application\Sync', 'host'));
        $database = Configuration::getInstance()->get_setting(array('Ehb\Application\Sync', 'database'));

        $data_source_name = DataSourceName::factory(
            'Doctrine',
            array('driver' => $dbms, 'username' => $user, 'host' => $host, 'name' => $database, 'password' => $password));

        $configuration = new \Doctrine\DBAL\Configuration();
        $connection_parameters = array(
            'dbname' => $data_source_name->get_database(),
            'user' => $data_source_name->get_username(),
            'password' => $data_source_name->get_password(),
            'host' => $data_source_name->get_host(),
            'driverClass' => $data_source_name->get_driver(true));
        $this->connection = DriverManager::getConnection($connection_parameters, $configuration);
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
