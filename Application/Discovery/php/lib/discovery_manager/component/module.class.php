<?php
namespace application\discovery;

class DiscoveryManagerModuleComponent extends DiscoveryManager
{

    function run()
    {
        ModuleInstanceManager :: launch($this);
    }
}
?>