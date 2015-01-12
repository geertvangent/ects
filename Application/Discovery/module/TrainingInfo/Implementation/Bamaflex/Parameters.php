<?php
namespace Chamilo\Application\Discovery\Module\TrainingInfo\Implementation\Bamaflex;

class Parameters extends \Chamilo\Application\Discovery\Module\TrainingInfo\Parameters
{

    public function __construct($training_id, $source, $tab)
    {
        parent :: __construct($training_id);
        $this->set_source($source);
        $this->set_tab($tab);
    }

    public function set_source($source)
    {
        $this->set_parameter(Module :: PARAM_SOURCE, $source);
    }

    public function get_source()
    {
        return $this->get_parameter(Module :: PARAM_SOURCE);
    }

    public function set_tab($tab)
    {
        $this->set_parameter(Module :: PARAM_TAB, $tab);
    }

    public function get_tab()
    {
        return $this->get_parameter(Module :: PARAM_TAB);
    }
}
