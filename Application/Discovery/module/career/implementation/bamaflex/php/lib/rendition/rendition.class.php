<?php
namespace application\discovery\module\career\implementation\bamaflex;

abstract class RenditionImplementation extends \application\discovery\RenditionImplementation
{

    public function get_courses()
    {
        return $this->get_module()->get_courses();
    }

    public function get_mark_moments()
    {
        return $this->get_module()->get_mark_moments();
    }
}
