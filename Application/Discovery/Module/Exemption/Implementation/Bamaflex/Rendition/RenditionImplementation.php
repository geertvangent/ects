<?php
namespace Ehb\Application\Discovery\Module\Exemption\Implementation\Bamaflex\Rendition;

abstract class RenditionImplementation extends \Ehb\Application\Discovery\Rendition\RenditionImplementation
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
