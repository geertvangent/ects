<?php
namespace application\atlantis\user_group;

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