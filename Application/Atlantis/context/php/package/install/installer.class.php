<?php
namespace application\atlantis\context;

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
