<?php
namespace Chamilo\Application\Discovery\Module\CourseResults\Implementation\Bamaflex\Rendition;

abstract class RenditionImplementation extends \Chamilo\Application\Discovery\RenditionImplementation
{

    public function get_course_results()
    {
        return $this->get_module()->get_course_results();
    }

    public function get_mark_moments()
    {
        return $this->get_module()->get_mark_moments();
    }
}
