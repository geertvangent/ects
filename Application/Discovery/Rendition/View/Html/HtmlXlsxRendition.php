<?php
namespace Ehb\Application\Discovery\Rendition\View\Html;

use Chamilo\Libraries\File\Filesystem;
use Chamilo\Libraries\File\Properties\FileProperties;
use Chamilo\Libraries\Platform\Translation;
use Ehb\Application\Discovery\Rendition\Format\HtmlRendition;
use Ehb\Application\Discovery\Rendition\RenditionImplementation;

class HtmlXlsxRendition extends HtmlRendition
{

    public function render()
    {
        $file_path = RenditionImplementation::launch(
            $this->get_module(), 
            \Ehb\Application\Discovery\Rendition\Rendition::FORMAT_XLSX, 
            \Ehb\Application\Discovery\Rendition\Rendition::VIEW_DEFAULT, 
            $this->get_context());
        
        $file_properties = FileProperties::from_path($file_path);
        
        if (Filesystem::file_send_for_download(
            $file_path, 
            true, 
            $file_properties->get_name_extension(), 
            $file_properties->get_type()))
        {
            Filesystem::remove($file_path);
            exit();
        }
        else
        {
            throw new \Exception(Translation::get('FileSendForDownloadFailed'));
        }
    }
}
