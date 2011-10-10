<?php
namespace application\discovery\module\enrollment;

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
     * @param ModuleInstance $module_instance
     * @return DataManagerInterface
     */
    static function get_instance($module_instance)
    {
        if (! isset(self :: $instance) || ! isset(self :: $instance[$module_instance->get_id()]))
        {
            require_once Path :: namespace_to_full_path($module_instance->get_type()) . 'php/lib/data_manager/data_source.class.php';
            $class = $module_instance->get_type() . '\\DataSource';
            self :: $instance[$module_instance->get_id()] = new $class($module_instance);
        }
        return self :: $instance[$module_instance->get_id()];
    }
}
?>