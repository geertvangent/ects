<?php
namespace Application\Discovery\module\profile;

use libraries\storage\data_class\DataClass;

class Photo extends DataClass
{
    const CLASS_NAME = __CLASS__;
    const PROPERTY_MIME_TYPE = 'mime_type';
    const PROPERTY_DATA = 'data';

    /**
     *
     * @return int
     */
    public function get_mime_type()
    {
        return $this->get_default_property(self :: PROPERTY_MIME_TYPE);
    }

    /**
     *
     * @return string A base 64 encoded representation of the photo
     */
    public function get_data()
    {
        return $this->get_default_property(self :: PROPERTY_DATA);
    }

    /**
     *
     * @param $mime_type int
     */
    public function set_mime_type($mime_type)
    {
        $this->set_default_property(self :: PROPERTY_MIME_TYPE, $mime_type);
    }

    /**
     *
     * @param $data string A base 64 encoded representation of the photo
     */
    public function set_data($data)
    {
        $this->set_default_property(self :: PROPERTY_DATA, $data);
    }

    /**
     *
     * @param $extended_property_names multitype:string
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_MIME_TYPE;
        $extended_property_names[] = self :: PROPERTY_DATA;
        
        return parent :: get_default_property_names($extended_property_names);
    }

    /**
     *
     * @return DataManagerInterface
     */
    public function get_data_manager()
    {
        // return DataManager :: get_instance();
    }

    public function get_source()
    {
        $source = array();
        $source[] = 'data';
        $source[] = ':';
        $source[] = $this->get_mime_type();
        $source[] = ';';
        $source[] = 'base64';
        $source[] = ',';
        $source[] = $this->get_data();
        
        return implode('', $source);
    }

    public function has_data()
    {
        if ($this->get_data())
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}
