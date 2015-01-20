<?php
namespace Chamilo\Application\Discovery\Module\Photo\Implementation\Bamaflex\Tables\PhotoGalleryTable;

use Chamilo\Libraries\Format\Table\Extension\GalleryTable\GalleryTablePropertyModel;
use Chamilo\Libraries\Format\Table\Extension\GalleryTable\Property\GalleryTableProperty;

class DefaultGalleryTablePropertyModel extends GalleryTablePropertyModel
{

    public function initialize_properties()
    {
        $this->add_property(new GalleryTableProperty(\Chamilo\Core\User\Storage\DataClass\User :: PROPERTY_LASTNAME));
        $this->add_property(new GalleryTableProperty(\Chamilo\Core\User\Storage\DataClass\User :: PROPERTY_FIRSTNAME));
    }
}
