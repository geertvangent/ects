<?php
namespace Chamilo\Application\Discovery\Module\Photo\Implementation\Bamaflex\Tables\PhotoGalleryTable;

use Chamilo\Libraries\Format\GalleryTablePropertyModel;
use Chamilo\Libraries\Format\GalleryTableProperty;

class DefaultGalleryTablePropertyModel extends GalleryTablePropertyModel
{

    public function initialize_properties()
    {
        $this->add_property(new GalleryTableProperty(\Chamilo\Core\User\User :: PROPERTY_LASTNAME));
        $this->add_property(new GalleryTableProperty(\Chamilo\Core\User\User :: PROPERTY_FIRSTNAME));
    }
}
