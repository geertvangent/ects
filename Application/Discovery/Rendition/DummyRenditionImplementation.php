<?php
namespace Ehb\Application\Discovery\Rendition;

use Ehb\Application\Discovery\Module;

class DummyRenditionImplementation extends AbstractRenditionImplementation
{

    private $format;

    private $view;

    public function __construct($context, Module $module, $format, $view)
    {
        parent::__construct($context, $module);
        $this->format = $format;
        $this->view = $view;
    }

    public function render()
    {
        return Rendition::launch($this);
    }

    public function get_view()
    {
        return $this->view;
    }

    public function get_format()
    {
        return $this->format;
    }
}
