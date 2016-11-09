<?php
namespace Ehb\Application\Discovery\Module\Course\Implementation\Bamaflex\Rendition\Html;

use Ehb\Application\Discovery\Module\Course\Implementation\Bamaflex\Rendition\RenditionImplementation;

class HtmlXlsxRenditionImplementation extends RenditionImplementation
{

    public function render()
    {
        \Ehb\Application\Discovery\Rendition\Rendition::launch($this);
    }

    /*
     * (non-PHPdoc) @see \application\discovery\AbstractRenditionImplementation::get_format()
     */
    public function get_format()
    {
        return \Ehb\Application\Discovery\Rendition\Rendition::FORMAT_HTML;
    }

    /*
     * (non-PHPdoc) @see \application\discovery\AbstractRenditionImplementation::get_view()
     */
    public function get_view()
    {
        return \Ehb\Application\Discovery\Rendition\Format\HtmlRendition::VIEW_XLSX;
    }
}
