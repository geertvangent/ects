<?php
namespace application\discovery\connection\bamaflex;

use application\discovery\ModuleInstance;

class DataSource extends \application\discovery\DataSource
{
    private $connection;

    /**
     * Constructor
     * @param ModuleInstance $module_instance
     */
    function __construct(ModuleInstance $module_instance)
    {
        parent :: __construct($module_instance);
        $this->initialize();
    }

    /**
     * Initialiser, creates the connection and sets the database to UTF8
     */
    function initialize()
    {
        $data_source = $this->get_module_instance()->get_setting('data_source');
        $this->connection = Connection :: get_instance($data_source)->get_connection();
        $this->connection->setOption('debug_handler', array(get_class($this), 'debug'));
        $this->connection->setOption('portability', MDB2_PORTABILITY_NONE);
        $this->connection->setCharset('utf8');
        
        // Necessary to retrieve complete photos and other large datasets from the database
        $this->connection->prepare('SET TEXTSIZE 2000000')->execute();
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

    /**
     * @param string $string
     * @return string
     */
    function convert_to_utf8($string)
    {
        return iconv('cp1252', 'UTF-8', $string);
    }
}
?>