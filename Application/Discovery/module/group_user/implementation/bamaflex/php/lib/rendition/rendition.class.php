<?php
namespace application\discovery\module\group_user\implementation\bamaflex;

abstract class RenditionImplementation extends \application\discovery\RenditionImplementation
{

    public function get_group_user()
    {
        return $this->get_module()->get_group_user();
    }
}
