<?php
namespace application\discovery\connection\bamaflex;

use application\discovery\DiscoveryModuleInstance;

class DataSource
{
    private $connection;
    private $module_instance;

    /**
     * Constructor
     * @param DiscoveryModuleInstance $discovery_module_instance
     */
    function __construct(DiscoveryModuleInstance $discovery_module_instance)
    {
        $this->module_instance = $discovery_module_instance;
        $this->initialize();
    }

    /**
     * Initialiser, creates the connection and sets the database to UTF8
     */
    function initialize()
    {
        $data_source = $this->module_instance->get_setting('data_source');
        $this->connection = Connection :: get_instance($data_source)->get_connection();
        $this->connection->setOption('debug_handler', array(get_class($this), 'debug'));
        $this->connection->setOption('portability', MDB2_PORTABILITY_NONE);
        $this->connection->setCharset('utf8');
    }

    /**
     * Returns the connection
     * @return Connection the connection
     */
    function get_connection()
    {
        return $this->connection;
    }

    /**
     * Sets the connection
     * @param Connection $connection
     */
    function set_connection($connection)
    {
        $this->connection = $connection;
    }
}
?>