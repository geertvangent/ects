<?php
namespace Application\Discovery\module\student_materials\implementation\bamaflex;

class Module extends \application\discovery\module\student_materials\Module
{

    public function has_enrollment_materials_by_type($year = null, $enrollment_id = null, $type = null)
    {
        return $this->get_data_manager()->count_materials($this->get_module_parameters(), $year, $enrollment_id, $type);
    }
}
