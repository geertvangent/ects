<?php
namespace Ehb\Application\Discovery\Module\Profile\Rendition;


abstract class Rendition extends \Ehb\Application\Discovery\Rendition\Rendition
{

    public function get_profile()
    {
        return $this->get_rendition_implementation()->get_profile();
    }
}
