<?php
namespace application\discovery\module\course\implementation\bamaflex;

abstract class RenditionImplementation extends \application\discovery\RenditionImplementation
{

    function get_course()
    {
        return $this->get_module()->get_course();
    }
}
