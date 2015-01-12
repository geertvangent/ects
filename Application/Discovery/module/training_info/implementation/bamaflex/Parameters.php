<?php
namespace Application\Discovery\module\training_info\implementation\bamaflex;

class Parameters extends \application\discovery\module\training_info\Parameters
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
