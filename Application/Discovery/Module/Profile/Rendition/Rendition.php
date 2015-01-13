<?php
namespace Chamilo\Application\Discovery\Module\Profile\Rendition;

use Chamilo\Application\Discovery\Rendition\Rendition;

abstract class Rendition extends Rendition
{

    public function get_profile()
    {
        return $this->get_rendition_implementation()->get_profile();
    }
}
