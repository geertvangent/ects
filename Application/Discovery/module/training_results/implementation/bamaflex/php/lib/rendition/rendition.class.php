<?php
namespace application\discovery\module\training_results\implementation\bamaflex;

abstract class RenditionImplementation extends \application\discovery\RenditionImplementation
{

    function get_training_results()
    {
        return $this->get_module()->get_training_results();
    }
}
