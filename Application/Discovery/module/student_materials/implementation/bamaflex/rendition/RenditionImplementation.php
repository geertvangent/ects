<?php
namespace Application\Discovery\module\student_materials\implementation\bamaflex\rendition;

abstract class RenditionImplementation extends \application\discovery\RenditionImplementation
{

    public function has_enrollment_materials_by_type($year = null, $enrollment_id = null, $type = null)
    {
        return $this->get_module()->has_enrollment_materials_by_type($year, $enrollment_id, $type);
    }
}
