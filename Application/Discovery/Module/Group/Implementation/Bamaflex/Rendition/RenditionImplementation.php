<?php
namespace Ehb\Application\Discovery\Module\Group\Implementation\Bamaflex\Rendition;

abstract class RenditionImplementation extends \Ehb\Application\Discovery\Rendition\RenditionImplementation
{

    public function has_groups($type)
    {
        return $this->get_module()->has_groups($type);
    }

    public function get_groups_data($type)
    {
        return $this->get_module()->get_groups_data($type);
    }
}
