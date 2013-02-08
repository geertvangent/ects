<?php
namespace application\discovery\module\course_results\implementation\bamaflex;

abstract class RenditionImplementation extends \application\discovery\RenditionImplementation
{

    function get_course_results()
    {
        return $this->get_module()->get_course_results();
    }

    function get_mark_moments()
    {
        return $this->get_module()->get_mark_moments();
    }
}
