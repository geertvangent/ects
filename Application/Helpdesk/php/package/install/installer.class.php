<?php
namespace application\ehb_helpdesk;

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
}
?>