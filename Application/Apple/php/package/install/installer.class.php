<?php
namespace application\ehb_apple;

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