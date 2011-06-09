<?php
namespace application\discovery;

use common\libraries\Application;

/**
 * @author Hans De Bisschop
 *
 */
class Module
{
    /**
     * @var Application
     */
    private $application;

    /**
     * @var DiscoveryModuleInstance
     */
    private $module_instance;

    /**
     * @param Application $application
     * @param DiscoveryModuleInstance $module_instance
     */
    function __construct(Application $application, DiscoveryModuleInstance $module_instance)
    {
        $this->application = $application;
        $this->module_instance = $module_instance;
    }

    /**
     * @param Application $application
     * @param DiscoveryModuleInstance $module_instance
     * @return Module
     */
    static public function factory(Application $application, DiscoveryModuleInstance $module_instance)
    {
        $class = $module_instance->get_type() . '\\Module';
        return new $class($application, $module_instance);
    }

    /**
     * @return string
     */
    function render()
    {
        return '';
    }

    /**
     * @return \application\discovery\DiscoveryModuleInstance
     */
    function get_module_instance()
    {
        return $this->module_instance;
    }

    /**
     * @return \application\discovery\Application
     */
    function get_application()
    {
        return $this->application;
    }
}
?>