<?php
namespace application\ehb_sync\bamaflex;

use common\libraries\DataSourceName;
use common\libraries\Application;
use common\libraries\PlatformSetting;
use MDB2;

/**
 * This class represents the current CAS Account database connection.
 * 
 * @author Hans De Bisschop
 */
class BamaflexConnection extends \common\libraries\Mdb2Connection
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
        $cas_dbms = PlatformSetting :: get('dbms', \application\ehb_sync\bamaflex\Manager :: context());
        $cas_user = PlatformSetting :: get('user', \application\ehb_sync\bamaflex\Manager :: context());
        $cas_password = PlatformSetting :: get('password', \application\ehb_sync\bamaflex\Manager :: context());
        $cas_host = PlatformSetting :: get('host', \application\ehb_sync\bamaflex\Manager :: context());
        $cas_database = PlatformSetting :: get('database', \application\ehb_sync\bamaflex\Manager :: context());
        
        $data_source_name = DataSourceName :: factory(
            'mdb2', 
            $cas_dbms, 
            $cas_user, 
            $cas_host, 
            $cas_database, 
            $cas_password);
        
        $this->connection = MDB2 :: connect($data_source_name->get_connection_string(), array('debug' => 3));
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
