<?php
namespace Chamilo\Application\Discovery\Module\TrainingResults\Implementation\Bamaflex\Rendition;

abstract class RenditionImplementation extends \Chamilo\Application\Discovery\Rendition\RenditionImplementation
{

    public function get_training_results()
    {
        return $this->get_module()->get_training_results();
    }
}
