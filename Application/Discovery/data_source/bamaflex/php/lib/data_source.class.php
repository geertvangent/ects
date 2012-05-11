<?php
namespace application\discovery\data_source\bamaflex;

use application\discovery\ModuleInstance;

class DataSource extends \application\discovery\DataSource
{
    private $connection;

    /**
     * Constructor
     *
     * @param $module_instance ModuleInstance
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
        // $this->connection->setOption('debug_handler', array(get_class($this),
        // 'debug'));
        // $this->connection->setOption('portability', MDB2_PORTABILITY_NONE);
        $this->connection->setCharset('utf8');
        if (Connection :: get_instance($data_source)->get_data_source_instance()->get_setting('driver') == 'mssql')
        {
            // Necessary to retrieve complete photos and other large datasets
            // from the database
            $this->connection->prepare('SET TEXTSIZE 2000000')->execute();
        }
    }

    /**
     * Returns the connection
     *
     * @return Connection the connection
     */
    function get_connection()
    {
        return $this->connection;
    }

    /**
     * Sets the connection
     *
     * @param $connection Connection
     */
    function set_connection($connection)
    {
        $this->connection = $connection;
    }

    /**
     *
     * @param $string string
     * @return string
     */
    function convert_to_utf8($string)
    {
        if (Connection :: get_instance($this->get_module_instance()->get_setting('data_source'))->get_data_source_instance()->get_setting('driver') == 'mssql')
        {
            return iconv('Windows-1252', 'UTF-8', $string);
        }
        else
        {
            return iconv(mb_detect_encoding($string), 'UTF-8', $string);
        }
    }
}
?>