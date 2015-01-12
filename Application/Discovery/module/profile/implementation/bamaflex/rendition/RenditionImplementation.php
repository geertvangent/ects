<?php
namespace Chamilo\Application\Discovery\Module\Profile\Implementation\Bamaflex\Rendition;

abstract class RenditionImplementation extends \Chamilo\Application\Discovery\RenditionImplementation
{

    public function get_profile()
    {
        return $this->get_module()->get_profile();
    }
}
