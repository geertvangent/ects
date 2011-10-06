<?php
namespace application\discovery\module\teaching_assignment;

use common\libraries\WebApplication;
use common\libraries\Utilities;
use common\libraries\Path;

use application\discovery\DiscoveryManager;

/**
 * @package application.discovery
 * @author Hans De Bisschop
 */
class DataManager
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
     * @param DiscoveryModuleInstance $discovery_module_instance
     * @return DataManagerInterface
     */
    static function get_instance($discovery_module_instance)
    {
        if (! isset(self :: $instance) || ! isset(self :: $instance[$discovery_module_instance->get_id()]))
        {
            require_once Path :: namespace_to_full_path($discovery_module_instance->get_type()) . 'php/lib/data_manager/data_source.class.php';
            $class = $discovery_module_instance->get_type() . '\\DataSource';
            self :: $instance[$discovery_module_instance->get_id()] = new $class($discovery_module_instance);
        }
        return self :: $instance[$discovery_module_instance->get_id()];
    }
}
?>