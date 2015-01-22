<?php
namespace Ehb\Application\Sync\Cas\Storage\DataManager\Connection;

use Chamilo\Libraries\Platform\Configuration\PlatformSetting;
use Chamilo\Libraries\Storage\DataManager\DataSourceName;

/**
 * This class represents the current CAS Account database connection.
 *
 * @author Hans De Bisschop
 */
class Mdb2Connection extends \Chamilo\Libraries\Storage\DataManager\Mdb2\Connection
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
        $cas_dbms = PlatformSetting :: get('dbms', \Ehb\Application\Sync\Cas\Storage\Manager :: context());
        $cas_user = PlatformSetting :: get('user', \Ehb\Application\Sync\Cas\Storage\Manager :: context());
        $cas_password = PlatformSetting :: get('password', \Ehb\Application\Sync\Cas\Storage\Manager :: context());
        $cas_host = PlatformSetting :: get('host', \Ehb\Application\Sync\Cas\Storage\Manager :: context());
        $cas_database = PlatformSetting :: get('database', \Ehb\Application\Sync\Cas\Storage\Manager :: context());

        $data_source_name = DataSourceName :: factory(
            'mdb2',
            $cas_dbms,
            $cas_user,
            $cas_host,
            $cas_database,
            $cas_password);

        $this->connection = \MDB2 :: connect($data_source_name->get_connection_string(), array('debug' => 3));
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
