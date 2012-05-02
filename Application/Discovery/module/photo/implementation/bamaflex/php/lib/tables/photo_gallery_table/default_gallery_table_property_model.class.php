<?php
namespace application\discovery\module\photo\implementation\bamaflex;

use user\User;

use common\libraries\GalleryObjectTablePropertyModel;
use common\libraries\GalleryObjectTableProperty;

class DefaultGalleryTablePropertyModel extends GalleryObjectTablePropertyModel
{

    /**
     * Constructor
     */
    function __construct()
    {
        parent :: __construct(self :: get_default_properties(), 0);
    }

    private static function get_default_properties()
    {        
        $properties = array();
        $properties[] = new GalleryObjectTableProperty(User::PROPERTY_LASTNAME);
        $properties[] = new GalleryObjectTableProperty(User::PROPERTY_FIRSTNAME);
        return $properties;
    }
}
?>