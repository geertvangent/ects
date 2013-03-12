<?php
namespace application\discovery\module\person\implementation\chamilo;

abstract class RenditionImplementation extends \application\discovery\RenditionImplementation
{

    public function get_group()
    {
        return $this->get_module()->get_group();
    }

    public function get_users_condition($query)
    {
        return $this->get_module()->get_users_condition($query);
    }

    public function get_subgroups_condition($query)
    {
        return $this->get_module()->get_subgroups_condition($query);
    }


}
