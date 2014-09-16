<?php
namespace application\discovery\module\cas\implementation\doctrine;

abstract class RenditionImplementation extends \application\discovery\RenditionImplementation
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
