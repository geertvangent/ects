<?php
namespace Application\Discovery\module\exemption\implementation\bamaflex\rendition;

abstract class RenditionImplementation extends \application\discovery\RenditionImplementation
{

    public function get_exemptions()
    {
        return $this->get_module()->get_exemptions();
    }

    public function get_exemptions_data($year)
    {
        return $this->get_module()->get_exemptions_data($year);
    }
}
