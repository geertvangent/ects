<?php
namespace application\atlantis\role\entity;

/**
 * Atlantis application
 *
 * @package application.atlantis
 */
class Installer extends \common\libraries\Installer
{

    /**
     * Constructor
     */
    function __construct($values)
    {
        
        parent :: __construct($values, DataManager :: get_instance());
    }

    function install_extra()
    {
        if (! $this->create_root())
        {
            return false;
        }
        return true;
    }
}
?>