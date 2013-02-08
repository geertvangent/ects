<?php
namespace application\discovery;

use common\libraries\WebApplicationInstaller;

/**
 *
 * @author Hans De Bisschop
 * @package application.discovery
 */
class DiscoveryInstaller extends WebApplicationInstaller
{

    /**
     * Constructor
     */
    function __construct($values)
    {
        parent :: __construct($values, DiscoveryDataManager :: get_instance());
    }
}
