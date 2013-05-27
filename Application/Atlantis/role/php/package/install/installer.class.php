<?php
namespace application\atlantis\role;

/**
 * Atlantis application
 *
 * @package application.atlantis
 */
class Installer extends \common\libraries\package\Installer
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
        $installers[] = 'application\atlantis\role\entitlement';
        $installers[] = 'application\atlantis\role\entity';
        return $installers;
    }
}
