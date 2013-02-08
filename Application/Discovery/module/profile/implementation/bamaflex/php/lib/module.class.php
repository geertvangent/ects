<?php
namespace application\discovery\module\profile\implementation\bamaflex;

use application\discovery\module\profile\DataManager;

class Module extends \application\discovery\module\profile\Module
{

    function has_data($parameters = null)
    {
        $parameters = $parameters ? $parameters : $this->get_module_parameters();
        return DataManager :: get_instance($this->get_module_instance())->has_profile($parameters);
    }
}
