<?php
namespace Chamilo\Application\Discovery\Rendition\View\Html;

use Chamilo\Application\Discovery\Rendition\Format\HtmlRendition;
use Chamilo\Application\Discovery\Rendition\RenditionImplementation;
use Chamilo\Libraries\File\Filesystem;
use Chamilo\Libraries\File\Properties\FileProperties;
use Chamilo\Libraries\Platform\Translation;

class HtmlZipRendition extends HtmlRendition
{

    public function render()
    {
        $file_path = RenditionImplementation :: launch(
            $this->get_module(),
            \Chamilo\Application\Discovery\Rendition\Rendition :: FORMAT_ZIP,
            \Chamilo\Application\Discovery\Rendition\Rendition :: VIEW_DEFAULT,
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
