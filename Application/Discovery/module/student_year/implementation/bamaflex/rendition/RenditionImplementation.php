<?php
namespace Application\Discovery\module\student_year\implementation\bamaflex\rendition;

abstract class RenditionImplementation extends \application\discovery\RenditionImplementation
{

    public function get_student_years()
    {
        return $this->get_module()->get_student_years();
    }
}
