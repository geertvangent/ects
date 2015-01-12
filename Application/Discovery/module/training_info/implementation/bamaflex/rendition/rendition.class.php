<?php
namespace Application\Discovery\module\training_info\implementation\bamaflex\rendition;

abstract class RenditionImplementation extends \application\discovery\RenditionImplementation
{

    public function get_training()
    {
        return $this->get_module()->get_training();
    }

    public function get_trainings_data($parameters)
    {
        return $this->get_module()->get_trainings_data($parameters);
    }
}
