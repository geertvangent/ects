<?php
namespace application\discovery\module\training_info\implementation\bamaflex;

class Parameters extends \application\discovery\module\training_info\Parameters
{

    function __construct($training_id, $source, $tab)
    {
        parent :: __construct($training_id);
        $this->set_source($source);
        $this->set_tab($tab);
    }

    function set_source($source)
    {
        $this->set_parameter(Module :: PARAM_SOURCE, $source);
    }

    function get_source()
    {
        return $this->get_parameter(Module :: PARAM_SOURCE);
    }

    function set_tab($tab)
    {
        $this->set_parameter(Module :: PARAM_TAB, $tab);
    }

    function get_tab()
    {
        return $this->get_parameter(Module :: PARAM_TAB);
    }
}
