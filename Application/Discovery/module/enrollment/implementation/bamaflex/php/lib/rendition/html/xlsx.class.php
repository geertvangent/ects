<?php
namespace application\discovery\module\enrollment\implementation\bamaflex;

use common\libraries\Filesystem;
use common\libraries\FileProperties;

class HtmlXlsxRenditionImplementation extends RenditionImplementation
{

    function render()
    {
        $file_path = RenditionImplementation :: launch($this->get_module(),
                \application\discovery\Rendition :: FORMAT_XLSX, \application\discovery\Rendition :: VIEW_DEFAULT,
                $this->get_context());

        $file_properties = FileProperties :: from_path($file_path);

        Filesystem :: file_send_for_download($file_path, true, $file_properties->get_name_extension(),
                $file_properties->get_type());
        exit();
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
?>