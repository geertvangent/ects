<?php
namespace application\discovery\module\person\implementation\chamilo;

abstract class RenditionImplementation extends \application\discovery\RenditionImplementation
{

    function get_group()
    {
        return $this->get_module()->get_group();
    }

    function get_users_condition($query)
    {
        return $this->get_module()->get_users_condition($query);
    }

    function get_subgroups_condition($query)
    {
        return $this->get_module()->get_subgroups_condition($query);
    }


}
