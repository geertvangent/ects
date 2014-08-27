<?php
namespace application\discovery\module\photo\implementation\bamaflex;

use libraries\GalleryObjectTablePropertyModel;
use libraries\GalleryObjectTableProperty;

class DefaultGalleryTablePropertyModel extends GalleryObjectTablePropertyModel
{

    /**
     * Constructor
     */
    public function __construct()
    {
        parent :: __construct(self :: get_default_properties(), 0);
    }

    private static function get_default_properties()
    {
        $properties = array();
        $properties[] = new GalleryObjectTableProperty(\core\user\User :: PROPERTY_LASTNAME);
        $properties[] = new GalleryObjectTableProperty(\core\user\User :: PROPERTY_FIRSTNAME);
        return $properties;
    }
}
