<?php
namespace application\discovery;

/**
 * @author Hans De Bisschop
 * @package application.discovery
 */

use application\discovery\module\profile\implementation\bamaflex\SettingsConnector;

use application\discovery\module\profile\DataManager;

class DiscoveryManagerBrowserComponent extends DiscoveryManager
{

    function run()
    {
        $this->display_header();

        $module_instance = DiscoveryDataManager :: get_instance()->retrieve_discovery_module_instance(1);
        var_dump($module_instance);

        //        $data_manager = DataManager :: get_instance($module_instance);
        //        var_dump($data_manager->retrieve_profile());

        var_dump(SettingsConnector :: get_data_sources());

        $this->display_footer();
    }
}
?>