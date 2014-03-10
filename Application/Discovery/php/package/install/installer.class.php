<?php
namespace application\discovery;

use common\libraries\WebApplicationInstaller;

/**
 *
 * @author Hans De Bisschop
 * @package application.discovery
 */
class Installer extends WebApplicationInstaller
{

    /**
     * Constructor
     */
    public function __construct($values)
    {
        parent :: __construct($values, DataManager :: get_instance());
    }

    public function get_additional_packages()
    {
        $installers = array();
        $installers[] = 'application\discovery\instance';
        
        return $installers;
    }
}
