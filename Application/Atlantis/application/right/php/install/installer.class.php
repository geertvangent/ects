<?php
namespace application\atlantis\application\right;

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

    function get_path()
    {
        return dirname(__FILE__);
    }

    function register_application()
    {
        return true;
    }
}
?>