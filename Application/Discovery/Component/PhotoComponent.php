<?php
namespace Ehb\Application\Discovery\Component;

use Chamilo\Libraries\Architecture\Interfaces\DelegateComponent;
use Chamilo\Libraries\File\Path;
use Chamilo\Libraries\Utilities\String\Text;
use Ehb\Application\Discovery\Manager;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 *
 * @package Ehb\Application\Discovery\Component$PhotoComponent
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 * @author Eduard Vossen <eduard.vossen@ehb.be>
 */
class PhotoComponent extends Manager implements DelegateComponent
{
    const PARAM_PHOTO = 'photo';

    public function run()
    {
        $photoIdentifier = $this->getRequest()->query->get(self::PARAM_PHOTO);
        
        $basePath = Path::getInstance()->getCachePath(
            'Ehb\Application\Discovery\Module\Photo\Implementation\Bamaflex\Photo');
        $identifierPath = Text::char_at($photoIdentifier, 0);
        $fileName = $photoIdentifier . '.jpg';
        
        $storagePath = $basePath . $identifierPath . DIRECTORY_SEPARATOR . $fileName;
        
        $type = exif_imagetype($storagePath);
        $mime = image_type_to_mime_type($type);
        $size = filesize($storagePath);
        
        $response = new StreamedResponse();
        $response->headers->add(array('Content-Type' => $mime, 'Content-Length' => $size));
        $response->setCallback(function () use ($storagePath)
        {
            readfile($storagePath);
        });
        
        $response->send();
        exit();
    }
}
