<?php
namespace Chamilo\Application\Discovery\Module\Person\Implementation\Chamilo\Rendition;

abstract class RenditionImplementation extends \Chamilo\Application\Discovery\Rendition\RenditionImplementation
{

    public function get_group()
    {
        return $this->get_module()->get_group();
    }

    public function get_users_condition($query)
    {
        return $this->get_module()->get_users_condition($query);
    }

    public function get_subgroups_condition($query)
    {
        return $this->get_module()->get_subgroups_condition($query);
    }
}
