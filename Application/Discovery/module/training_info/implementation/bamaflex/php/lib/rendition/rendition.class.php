<?php
namespace application\discovery\module\training_info\implementation\bamaflex;

abstract class RenditionImplementation extends \application\discovery\RenditionImplementation
{

    function get_training()
    {
        return $this->get_module()->get_training();
    }

    function get_trainings_data($parameters)
    {
        return $this->get_module()->get_trainings_data($parameters);
    }
}
?>