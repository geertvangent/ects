<?php
namespace application\atlantis;

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

    function get_additional_installers()
    {
        $installers = array();

        $installers[] = new \application\atlantis\application\Installer($this->get_form_values());
        $installers[] = new \application\atlantis\application\right\Installer($this->get_form_values());
        $installers[] = new \application\atlantis\role\entitlement\Installer($this->get_form_values());
        $installers[] = new \application\atlantis\role\entity\Installer($this->get_form_values());
        $installers[] = new \application\atlantis\role\Installer($this->get_form_values());
        $installers[] = new \application\atlantis\context\Installer($this->get_form_values());

        return $installers;
    }

}
?>