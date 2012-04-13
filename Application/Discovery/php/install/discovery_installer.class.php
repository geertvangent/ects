<?php
namespace application\discovery;

use common\libraries\WebApplication;
use common\libraries\Installer;

/**
 * @author Hans De Bisschop
 * @package application.discovery
 */

require_once WebApplication :: get_application_class_lib_path('discovery') . 'discovery_data_manager.class.php';

class DiscoveryInstaller extends Installer
{

    /**
     * Constructor
     */
    function __construct($values)
    {
        parent :: __construct($values, DiscoveryDataManager :: get_instance());
    }

    function get_path()
    {
        return parent :: get_path();
    }
}
?>