<?php
namespace Application\Discovery\module\faculty_info\implementation\bamaflex\rendition;

abstract class RenditionImplementation extends \application\discovery\RenditionImplementation
{

    public function get_faculty()
    {
        return $this->get_module()->get_faculty();
    }

    public function get_trainings_data($parameters)
    {
        return $this->get_module()->get_trainings_data($parameters);
    }
}
