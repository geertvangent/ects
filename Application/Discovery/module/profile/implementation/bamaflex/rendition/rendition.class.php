<?php
namespace application\discovery\module\profile\implementation\bamaflex;

abstract class RenditionImplementation extends \application\discovery\RenditionImplementation
{

    public function get_profile()
    {
        return $this->get_module()->get_profile();
    }
}
