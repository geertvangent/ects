<?php
namespace application\discovery\module\course\implementation\bamaflex;

abstract class RenditionImplementation extends \application\discovery\RenditionImplementation
{

    public function get_course()
    {
        return $this->get_module()->get_course();
    }
}
