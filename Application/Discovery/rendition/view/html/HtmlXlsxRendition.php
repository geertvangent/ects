<?php
namespace Chamilo\Application\Discovery\Rendition\View\Html;

use Chamilo\Libraries\Platform\Translation\Translation;
use Chamilo\Libraries\File\Filesystem;
use Chamilo\Libraries\File\FileProperties;

class HtmlXlsxRendition extends HtmlRendition
{

    public function render()
    {
        $file_path = RenditionImplementation :: launch(
            $this->get_module(), 
            \Chamilo\Application\Discovery\Rendition :: FORMAT_XLSX, 
            \Chamilo\Application\Discovery\Rendition :: VIEW_DEFAULT, 
            $this->get_context());
        
        $file_properties = FileProperties :: from_path($file_path);
        
        if (Filesystem :: file_send_for_download(
            $file_path, 
            true, 
            $file_properties->get_name_extension(), 
            $file_properties->get_type()))
        {
            Filesystem :: remove($file_path);
            exit();
        }
        else
        {
            throw new \Chamilo\Exception(Translation :: get('FileSendForDownloadFailed'));
        }
    }
}
