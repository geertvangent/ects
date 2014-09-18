<?php
namespace application\discovery\module\group\implementation\bamaflex;

abstract class RenditionImplementation extends \application\discovery\RenditionImplementation
{

    public function has_groups($type)
    {
        return $this->get_module()->has_groups($type);
    }

    public function get_groups_data($type)
    {
        return $this->get_module()->get_groups_data($type);
    }
}
