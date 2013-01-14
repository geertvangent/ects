<?php
namespace application\discovery;

class DummyRenditionImplementation extends AbstractRenditionImplementation
{

    private $format;

    private $view;

    function __construct($context, Module $module, $format, $view)
    {
        parent :: __construct($context, $module);
        $this->format = $format;
        $this->view = $view;
    }

    function render()
    {
        return Rendition :: launch($this);
    }

    function get_view()
    {
        return $this->view;
    }

    function get_format()
    {
        return $this->format;
    }
}
?>