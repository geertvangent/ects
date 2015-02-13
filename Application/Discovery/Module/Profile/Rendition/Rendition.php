<?php
namespace Ehb\Application\Discovery\Module\Profile\Rendition;

use Ehb\Application\Discovery\Rendition\Rendition;

abstract class Rendition extends Rendition
{

    public function get_profile()
    {
        return $this->get_rendition_implementation()->get_profile();
    }
}
