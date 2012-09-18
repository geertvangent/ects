<?php
namespace application\ehb_sync;

/**
 *
 * @author Hans De Bisschop
 */
use common\libraries\WebApplicationInstaller;

class Installer extends WebApplicationInstaller
{

    /**
     * Constructor
     */
    function __construct($values)
    {
        parent :: __construct($values, DataManager :: get_instance());
    }

    function get_additional_installers()
    {
        $installers = array();
        $installers[] = new \application\ehb_sync\bamaflex\Installer($this->get_form_values());
        return $installers;
    }
}
?>