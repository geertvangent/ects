<?php
namespace application\discovery\module\exemption\implementation\bamaflex;

abstract class RenditionImplementation extends \application\discovery\RenditionImplementation
{

    function get_exemptions()
    {
        return $this->get_module()->get_exemptions();
    }

    function get_exemptions_data($year)
    {
        return $this->get_module()->get_exemptions_data($year);
    }
}
