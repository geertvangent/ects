<?php
namespace application\discovery;

/**
 * @author Hans De Bisschop
 * @package application.discovery
 */
class DiscoveryManagerBrowserComponent extends DiscoveryManager
{

    function run()
    {
        $this->display_header();

        var_dump(DiscoveryDataManager::get_instance()->retrieve_discovery_module_instance(1));

        $this->display_footer();
    }
}
?>