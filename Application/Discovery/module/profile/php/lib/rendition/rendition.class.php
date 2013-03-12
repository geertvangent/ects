<?php
namespace application\discovery\module\profile;

abstract class Rendition extends \application\discovery\Rendition
{
    public function get_profile()
    {
        return $this->get_rendition_implementation()->get_profile();
    }
}
