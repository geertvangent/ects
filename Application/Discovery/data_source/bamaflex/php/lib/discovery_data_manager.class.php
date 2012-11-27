<?php
namespace application\discovery\data_source\bamaflex;

use common\libraries\Configuration;
use common\libraries\WebApplication;
use common\libraries\Utilities;

/**
 * @package application.discovery
 * @author Hans De Bisschop
 */
class DiscoveryDataManager
{
    /**
     * Instance of this class for the singleton pattern.
     */
    private static $instance;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->initialize();
    }

    /**
     * Uses a singleton pattern and a factory pattern to return the data
     * manager. The configuration determines which data manager class is to
     * be instantiated.
     * @return DiscoveryDataManagerInterface The data manager.
     */
    static function get_instance()
    {
        if (! isset(self :: $instance))
        {
            $type = Configuration :: get_instance()->get_parameter('general', 'data_manager');
            $class = __NAMESPACE__ . '\\' . Utilities :: underscores_to_camelcase($type) . 'DiscoveryDataManager';
            self :: $instance = new $class();
        }
        return self :: $instance;
    }
}
?>