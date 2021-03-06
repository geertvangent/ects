<?php
namespace Ehb\Application\Discovery\DataSource\Doctrine;

use Ehb\Application\Discovery\Instance\Storage\DataClass\Instance;

class DataSource extends \Ehb\Application\Discovery\DataSource
{

    private $connection;

    /**
     * Constructor
     * 
     * @param $module_instance Instance
     */
    public function __construct(Instance $module_instance)
    {
        parent::__construct($module_instance);
        $this->initialize();
    }

    /**
     * Initialiser, creates the connection and sets the database to UTF8
     */
    public function initialize()
    {
        $data_source = $this->get_module_instance()->get_setting('data_source');
        $this->connection = Connection::getInstance($data_source)->get_connection();
        $this->connection->setCharset('utf8');
    }

    /**
     * Returns the connection
     * 
     * @return Connection the connection
     */
    public function get_connection()
    {
        return $this->connection;
    }

    /**
     * Sets the connection
     * 
     * @param $connection Connection
     */
    public function set_connection($connection)
    {
        $this->connection = $connection;
    }
}
