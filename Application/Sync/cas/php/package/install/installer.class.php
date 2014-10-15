<?php
namespace application\ehb_sync\cas;

/**
 *
 * @author Hans De Bisschop
 */
class Installer extends \configuration\package\Installer
{

    /**
     * Constructor
     */
    public function __construct($values)
    {
        parent :: __construct($values, DataManager :: get_instance());
    }
}
