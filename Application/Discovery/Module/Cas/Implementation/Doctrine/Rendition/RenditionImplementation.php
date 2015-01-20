<?php
namespace Chamilo\Application\Discovery\Module\Cas\Implementation\Doctrine\Rendition;

abstract class RenditionImplementation extends \Chamilo\Application\Discovery\Rendition\RenditionImplementation
{

    public function get_cas_statistics()
    {
        return $this->get_module()->get_cas_statistics();
    }

    public function get_action_statistics($action)
    {
        return $this->get_module()->get_action_statistics($action);
    }

    public function get_applications()
    {
        return $this->get_module()->get_applications();
    }
}
