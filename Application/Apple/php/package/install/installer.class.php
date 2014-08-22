<?php
namespace application\ehb_apple;

use libraries\WebApplicationInstaller;

/**
 *
 * @author Hans De Bisschop
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
}
