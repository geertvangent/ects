<?php
namespace Ehb\Application\Discovery\Module\Profile\Implementation\Bamaflex;

use Ehb\Application\Discovery\Module\Profile\DataManager;

class Module extends \Ehb\Application\Discovery\Module\Profile\Module
{

    public function has_data($parameters = null)
    {
        $parameters = $parameters ? $parameters : $this->get_module_parameters();
        return DataManager :: getInstance($this->get_module_instance())->has_profile($parameters);
    }
}
