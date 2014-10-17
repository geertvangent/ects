<?php
namespace application\discovery\module\photo\implementation\bamaflex;

use libraries\format\GalleryTablePropertyModel;
use libraries\format\GalleryTableProperty;

class DefaultGalleryTablePropertyModel extends GalleryTablePropertyModel
{

    public function initialize_properties()
    {
        $this->add_property(new GalleryTableProperty(\core\user\User :: PROPERTY_LASTNAME));
        $this->add_property(new GalleryTableProperty(\core\user\User :: PROPERTY_FIRSTNAME));
    }
}
