<?php
namespace application\discovery\module\employment\implementation\bamaflex;

class HtmlXlsxRenditionImplementation extends RenditionImplementation
{

    public function render()
    {
        \application\discovery\Rendition :: launch($this);
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
        return \application\discovery\HtmlRendition :: VIEW_XLSX;
    }
}
