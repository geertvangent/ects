<?php
namespace application\atlantis\context;

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
}
?>