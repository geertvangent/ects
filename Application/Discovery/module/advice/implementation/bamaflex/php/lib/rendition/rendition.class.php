<?php
namespace application\discovery\module\advice\implementation\bamaflex;

abstract class RenditionImplementation extends \application\discovery\RenditionImplementation
{

    function has_advices($enrollment)
    {
        return $this->get_module()->has_advices($enrollment);
    }

    function get_advices_data($enrollment)
    {
        return $this->get_module()->get_advices_data($enrollment);
    }
}
