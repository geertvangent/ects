<?php
namespace application\discovery\connection\bamaflex;

use application\discovery\DiscoveryDataManager;

use common\libraries\Mdb2Connection;

use MDB2;

class Connection extends Mdb2Connection
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
    private function __construct($data_source_instance_id)
    {
        $this->data_source_instance = DiscoveryDataManager :: get_instance()->retrieve_data_source_instance($data_source_instance_id);

        $host = $this->data_source_instance->get_setting('host');
        $username = $this->data_source_instance->get_setting('username');
        $password = $this->data_source_instance->get_setting('password');
        $database = $this->data_source_instance->get_setting('database');

        $this->connection = MDB2 :: connect('mssql://' . $username . ':' . $password . '@' . $host . '/' . $database, array(
                'debug' => 3));
    }

    /**
     * Returns the instance of this class.
     * @return Connection The instance.
     */
    static function get_instance($data_source_instance_id)
    {
        if (! isset(self :: $instance) || ! isset(self :: $instance[$data_source_instance_id]))
        {
            self :: $instance[$data_source_instance_id] = new self($data_source_instance_id);
        }
        return self :: $instance[$data_source_instance_id];
    }

    /**
     * Gets the database connection.
     * @return mixed MDB2 DB Connection.
     */
    function get_connection()
    {
        return $this->connection;
    }

    function set_option($option, $value)
    {
        $this->connection->setOption($option, $value);
    }
}
?>