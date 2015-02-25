<?php
namespace Ehb\Application\Discovery\Module\GroupUser\Implementation\Bamaflex\Rendition;

abstract class RenditionImplementation extends \Ehb\Application\Discovery\Rendition\RenditionImplementation
{

    public function get_group_user()
    {
        return $this->get_module()->get_group_user();
    }
}
