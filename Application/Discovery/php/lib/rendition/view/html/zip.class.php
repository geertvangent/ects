<?php
namespace application\discovery;

use libraries\Translation;
use libraries\Filesystem;
use libraries\FileProperties;

class HtmlZipRendition extends HtmlRendition
{

    public function render()
    {
        $file_path = RenditionImplementation :: launch(
            $this->get_module(), 
            \application\discovery\Rendition :: FORMAT_ZIP, 
            \application\discovery\Rendition :: VIEW_DEFAULT, 
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
            throw new \Exception(Translation :: get('FileSendForDownloadFailed'));
        }
    }
}
