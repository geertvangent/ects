<?php
namespace Chamilo\Application\Discovery\Module\Profile\Implementation\Bamaflex;

use Chamilo\Application\Discovery\Module\Profile\DataManager;

class Module extends \Chamilo\Application\Discovery\Module\Profile\Module
{

    public function has_data($parameters = null)
    {
        $parameters = $parameters ? $parameters : $this->get_module_parameters();
        return DataManager :: get_instance($this->get_module_instance())->has_profile($parameters);
    }
}
