<?php
namespace application\discovery;

class DiscoveryManagerModuleComponent extends DiscoveryManager
{

    function run()
    {
        DiscoveryModuleInstanceManager :: launch($this);
    }
}
?>