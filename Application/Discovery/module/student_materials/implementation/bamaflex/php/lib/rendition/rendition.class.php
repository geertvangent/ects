<?php
namespace application\discovery\module\student_materials\implementation\bamaflex;

abstract class RenditionImplementation extends \application\discovery\RenditionImplementation
{

    function has_enrollment_materials_by_type($year = null, $enrollment_id = null, $type = null)
    {
        return $this->get_module()->has_enrollment_materials_by_type($year, $enrollment_id, $type);
    }
}
