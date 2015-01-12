<?php
namespace Chamilo\Application\Discovery\Module\TrainingInfo\Implementation\Bamaflex\Rendition;

abstract class RenditionImplementation extends \Chamilo\Application\Discovery\RenditionImplementation
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
