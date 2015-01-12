<?php
namespace Chamilo\Application\Discovery\Module\Profile\Rendition;

abstract class Rendition extends \Chamilo\Application\Discovery\Rendition
{

    public function get_profile()
    {
        return $this->get_rendition_implementation()->get_profile();
    }
}
