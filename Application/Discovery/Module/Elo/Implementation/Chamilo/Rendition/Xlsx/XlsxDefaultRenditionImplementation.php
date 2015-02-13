<?php
namespace Ehb\Application\Discovery\Module\Elo\Implementation\Chamilo\Rendition\Xlsx;

use Ehb\Application\Discovery\Module\Elo\Implementation\Chamilo\Rendition\RenditionImplementation;

class XlsxDefaultRenditionImplementation extends RenditionImplementation
{
    
    /*
     * (non-PHPdoc) @see \application\discovery\AbstractRenditionImplementation::get_format()
     */
    public function get_format()
    {
        return \Ehb\Application\Discovery\Rendition\Rendition :: FORMAT_XLSX;
    }
    
    /*
     * (non-PHPdoc) @see \application\discovery\AbstractRenditionImplementation::get_view()
     */
    public function get_view()
    {
        return \Ehb\Application\Discovery\Rendition\Rendition :: VIEW_DEFAULT;
    }
}
