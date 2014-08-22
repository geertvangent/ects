<?php
namespace application\ehb_helpdesk;

/**
 *
 * @author Hans De Bisschop
 */
use libraries\WebApplicationInstaller;

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
