<?php
namespace application\discovery;

class DataSource
{
    private $module_instance;

    /**
     * Constructor
     * @param DiscoveryModuleInstance $discovery_module_instance
     */
    function __construct(DiscoveryModuleInstance $module_instance)
    {
        $this->module_instance = $module_instance;
    }

    function get_module_instance()
    {
        return $this->module_instance;
    }

    function set_module_instance(DiscoveryModuleInstance $module_instance)
    {
        $this->module_instance = $module_instance;
    }
}
?>