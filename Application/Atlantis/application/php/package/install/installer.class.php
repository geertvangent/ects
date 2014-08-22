<?php
namespace application\atlantis\application;

/**
 * Atlantis application
 *
 * @package application.atlantis
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

    public function get_additional_packages()
    {
        $installers = array();
        $installers[] = 'application\atlantis\application\right';
        return $installers;
    }
}
