<?php
namespace application\discovery\module\profile\implementation\bamaflex;

use application\discovery\module\profile\DataManager;

class Module extends \application\discovery\Module
{

    function render()
    {
        $html = array();

        $data_manager = DataManager :: get_instance($this->get_module_instance());
        $profile = $data_manager->retrieve_profile($this->get_application()->get_user_id());

        if ($profile instanceof Profile)
        {
            $html[] = '<img src="' . $profile->get_photo()->get_source() . '"/>';
            $html[] = '<pre>';
            $html[] = print_r($profile, true);
            $html[] = '</pre>';
        }

        return implode("\n", $html);
    }
}
?>