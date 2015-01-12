<?php
namespace Chamilo\Application\Discovery\Module\TeachingAssignment\Implementation\Bamaflex\Rendition;

abstract class RenditionImplementation extends \Chamilo\Application\Discovery\RenditionImplementation
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
