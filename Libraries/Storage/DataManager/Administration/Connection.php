<?php
namespace Ehb\Libraries\Storage\DataManager\Administration;

use Chamilo\Configuration\Configuration;
use Chamilo\Libraries\Storage\DataManager\DataSourceName;
use Doctrine\DBAL\DriverManager;

/**
 *
 * @package Ehb\Libraries\Storage\DataManager\Administration
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
class Connection
{

    /**
     *
     * @var \Ehb\Libraries\Storage\DataManager\Administration\Connection
     */
    private static $instance;

    /**
     *
     * @var \Doctrine\DBAL\Connection
     */
    protected $connection;

    /**
     * Constructor
     */
    private function __construct()
    {
        $configuration = Configuration::get_instance();

        $data_source_name = DataSourceName::factory(
            'Doctrine',
            $configuration->get_setting(array('Ehb\Libraries', 'administration_driver')),
            $configuration->get_setting(array('Ehb\Libraries', 'administration_username')),
            $configuration->get_setting(array('Ehb\Libraries', 'administration_host')),
            $configuration->get_setting(array('Ehb\Libraries', 'administration_database')),
            $configuration->get_setting(array('Ehb\Libraries', 'administration_password')));

        $this->connection = DriverManager::getConnection(
            array(
                'dbname' => $data_source_name->get_database(),
                'user' => $data_source_name->get_username(),
                'password' => $data_source_name->get_password(),
                'host' => $data_source_name->get_host(),
                'driverClass' => $data_source_name->get_driver(true)),
            new \Doctrine\DBAL\Configuration());
    }

    /**
     *
     * @return \Ehb\Libraries\Storage\DataManager\Administration\Connection
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
     *
     * @return \Doctrine\DBAL\Connection
     */
    public function getConnection()
    {
        return $this->connection;
    }
}
