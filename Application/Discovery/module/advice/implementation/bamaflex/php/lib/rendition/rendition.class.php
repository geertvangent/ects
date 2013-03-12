<?php
namespace application\discovery\module\advice\implementation\bamaflex;

abstract class RenditionImplementation extends \application\discovery\RenditionImplementation
{

    public function has_advices($enrollment)
    {
        return $this->get_module()->has_advices($enrollment);
    }

    public function get_advices_data($enrollment)
    {
        return $this->get_module()->get_advices_data($enrollment);
    }
}
