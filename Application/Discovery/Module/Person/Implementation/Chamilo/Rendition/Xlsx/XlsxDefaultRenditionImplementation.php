<?php
namespace Chamilo\Application\Discovery\Module\Person\Implementation\Chamilo\Rendition\Xlsx;

use Chamilo\Application\Discovery\Module\Person\Implementation\Chamilo\Rendition\RenditionImplementation;

class XlsxDefaultRenditionImplementation extends RenditionImplementation
{
    
    /*
     * (non-PHPdoc) @see \application\discovery\AbstractRenditionImplementation::get_format()
     */
    public function get_format()
    {
        return \Chamilo\Application\Discovery\Rendition\Rendition :: FORMAT_XLSX;
    }
    
    /*
     * (non-PHPdoc) @see \application\discovery\AbstractRenditionImplementation::get_view()
     */
    public function get_view()
    {
        return \Chamilo\Application\Discovery\Rendition\Rendition :: VIEW_DEFAULT;
    }
}
