<?php
namespace application\discovery\module\profile\implementation\bamaflex;

abstract class RenditionImplementation extends \application\discovery\RenditionImplementation
{

    function get_profile_parameters()
    {
        return $this->get_module()->get_profile_parameters();
    }

    function get_profile()
    {
        return $this->get_module()->get_profile();
    }
}
?>