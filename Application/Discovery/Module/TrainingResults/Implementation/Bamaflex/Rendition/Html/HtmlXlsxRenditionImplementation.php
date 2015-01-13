<?php
namespace Chamilo\Application\Discovery\Module\TrainingResults\Implementation\Bamaflex\Rendition\Html;

use Chamilo\Application\Discovery\Module\TrainingResults\Implementation\Bamaflex\Rendition\RenditionImplementation;

class HtmlXlsxRenditionImplementation extends RenditionImplementation
{

    public function render()
    {
        \Chamilo\Application\Discovery\Rendition\Rendition :: launch($this);
    }

    /*
     * (non-PHPdoc) @see \application\discovery\AbstractRenditionImplementation::get_format()
     */
    public function get_format()
    {
        return \Chamilo\Application\Discovery\Rendition\Rendition :: FORMAT_HTML;
    }

    /*
     * (non-PHPdoc) @see \application\discovery\AbstractRenditionImplementation::get_view()
     */
    public function get_view()
    {
        return \Chamilo\Application\Discovery\Rendition\Format\HtmlRendition :: VIEW_XLSX;
    }
}
