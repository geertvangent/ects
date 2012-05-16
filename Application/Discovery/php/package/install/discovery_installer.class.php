<?php
namespace application\discovery;

use common\libraries\package\Installer;

/**
 * @author Hans De Bisschop
 * @package application.discovery
 */

class DiscoveryInstaller extends Installer
{

    /**
     * Constructor
     */
    function __construct($values)
    {
        parent :: __construct($values, DiscoveryDataManager :: get_instance());
    }

}
?>