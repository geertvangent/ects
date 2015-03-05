<?php
namespace Ehb\Application\Discovery\Module\Group\Implementation\Bamaflex;

class Parameters extends \Ehb\Application\Discovery\Module\Group\Parameters
{

    public function __construct($training_id, $source)
    {
        parent :: __construct($training_id);
        $this->set_source($source);
    }

    public function set_source($source)
    {
        $this->set_parameter(Module :: PARAM_SOURCE, $source);
    }

    public function get_source()
    {
        return $this->get_parameter(Module :: PARAM_SOURCE);
    }
}
