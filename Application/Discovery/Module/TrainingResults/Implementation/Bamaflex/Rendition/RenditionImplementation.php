<?php
namespace Ehb\Application\Discovery\Module\TrainingResults\Implementation\Bamaflex\Rendition;

abstract class RenditionImplementation extends \Ehb\Application\Discovery\Rendition\RenditionImplementation
{

    public function get_training_results()
    {
        return $this->get_module()->get_training_results();
    }
}
