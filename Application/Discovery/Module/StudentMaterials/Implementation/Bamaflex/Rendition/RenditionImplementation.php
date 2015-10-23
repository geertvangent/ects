<?php
namespace Ehb\Application\Discovery\Module\StudentMaterials\Implementation\Bamaflex\Rendition;

abstract class RenditionImplementation extends \Ehb\Application\Discovery\Rendition\RenditionImplementation
{

    public function has_enrollment_materials_by_type($year = null, $enrollment_id = null, $type = null)
    {
        return $this->get_module()->has_enrollment_materials_by_type($year, $enrollment_id, $type);
    }
}
