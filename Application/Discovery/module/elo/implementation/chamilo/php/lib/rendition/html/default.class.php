<?php
namespace application\discovery\module\elo\implementation\chamilo;

class HtmlDefaultRenditionImplementation extends RenditionImplementation
{

    public function render()
    {
        $html = array();
        $html[] = 'Content goes here';

        return implode("\n", $html);
    }

    /*
     * (non-PHPdoc) @see \application\discovery\AbstractRenditionImplementation::get_format()
     */
    public function get_format()
    {
        return \application\discovery\Rendition :: FORMAT_HTML;
    }

    /*
     * (non-PHPdoc) @see \application\discovery\AbstractRenditionImplementation::get_view()
     */
    public function get_view()
    {
        return \application\discovery\Rendition :: VIEW_DEFAULT;
    }
}
