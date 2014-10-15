<?php
namespace application\discovery\module\enrollment\implementation\bamaflex;

abstract class RenditionImplementation extends \application\discovery\RenditionImplementation
{

    public function get_enrollments()
    {
        return $this->get_module()->get_enrollments();
    }
}
