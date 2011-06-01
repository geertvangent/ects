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
        $this->display_footer();
    }
}
?>