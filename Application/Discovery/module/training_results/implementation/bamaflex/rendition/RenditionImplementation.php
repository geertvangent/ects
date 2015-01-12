<?php
namespace Application\Discovery\module\training_results\implementation\bamaflex\rendition;

abstract class RenditionImplementation extends \application\discovery\RenditionImplementation
{

    public function get_training_results()
    {
        return $this->get_module()->get_training_results();
    }
}
