<?php
namespace application\discovery\module\faculty\implementation\bamaflex;


class HtmlXlsxRenditionImplementation extends RenditionImplementation
{

    function render()
    {
        \application\discovery\Rendition :: launch($this);
    }

    /*
     * (non-PHPdoc) @see \application\discovery\AbstractRenditionImplementation::get_format()
     */
    function get_format()
    {
        return \application\discovery\Rendition :: FORMAT_HTML;
    }

    /*
     * (non-PHPdoc) @see \application\discovery\AbstractRenditionImplementation::get_view()
     */
    function get_view()
    {
        return \application\discovery\HtmlRendition :: VIEW_XLSX;
    }
}
