<?php
namespace Ehb\Application\Discovery\Module\TeachingAssignment\Implementation\Bamaflex\Rendition;

abstract class RenditionImplementation extends \Ehb\Application\Discovery\Rendition\RenditionImplementation
{

    public function get_teaching_assignments_data($parameters)
    {
        return $this->get_module()->get_teaching_assignments_data($parameters);
    }

    public function get_years()
    {
        return $this->get_module()->get_years();
    }
}
