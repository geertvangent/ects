<?php
namespace Chamilo\Application\Discovery\Module\StudentMaterials\Implementation\Bamaflex;

class Module extends \Chamilo\Application\Discovery\Module\StudentMaterials\Module
{

    public function has_enrollment_materials_by_type($year = null, $enrollment_id = null, $type = null)
    {
        return $this->get_data_manager()->count_materials($this->get_module_parameters(), $year, $enrollment_id, $type);
    }
}
