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
    public function __construct($values)
    {
        parent :: __construct($values, DataManager :: get_instance());
    }
}
