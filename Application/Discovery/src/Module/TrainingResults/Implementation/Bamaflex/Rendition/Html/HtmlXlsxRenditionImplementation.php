<?php
namespace Chamilo\Application\Discovery\Module\TrainingResults\Implementation\Bamaflex\Rendition\Html;

class HtmlXlsxRenditionImplementation extends RenditionImplementation
{

    public function render()
    {
        \Chamilo\Application\Discovery\Rendition :: launch($this);
    }
    
    /*
     * (non-PHPdoc) @see \application\discovery\AbstractRenditionImplementation::get_format()
     */
    public function get_format()
    {
        return \Chamilo\Application\Discovery\Rendition :: FORMAT_HTML;
    }
    
    /*
     * (non-PHPdoc) @see \application\discovery\AbstractRenditionImplementation::get_view()
     */
    public function get_view()
    {
        return \Chamilo\Application\Discovery\HtmlRendition :: VIEW_XLSX;
    }
}
