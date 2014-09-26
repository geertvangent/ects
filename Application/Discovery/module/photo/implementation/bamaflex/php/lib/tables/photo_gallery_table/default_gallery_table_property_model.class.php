<?php
namespace application\discovery\module\photo\implementation\bamaflex;

use libraries\GalleryTablePropertyModel;
use libraries\GalleryTableProperty;

class DefaultGalleryTablePropertyModel extends GalleryTablePropertyModel
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
        $properties[] = new GalleryTableProperty(\core\user\User :: PROPERTY_LASTNAME);
        $properties[] = new GalleryTableProperty(\core\user\User :: PROPERTY_FIRSTNAME);
        return $properties;
    }
    /*
     * (non-PHPdoc) @see \libraries\GalleryTablePropertyModel::initialize_properties()
     */
    public function initialize_properties()
    {
        // TODO Auto-generated method stub
    }
}
