<?php
namespace application\atlantis\role\entity;

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
}
