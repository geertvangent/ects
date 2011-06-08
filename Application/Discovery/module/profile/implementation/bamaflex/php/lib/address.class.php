<?php
namespace application\discovery\module\profile\implementation\bamaflex;

use application\discovery\DiscoveryDataManager;

use common\libraries\DataClass;

class Address extends DataClass
{
    const CLASS_NAME = __CLASS__;

    const PROPERTY_TYPE = 'type';
    const PROPERTY_COUNTRY = 'country';
    const PROPERTY_STREET = 'street';
    const PROPERTY_NUMBER = 'number';
    const PROPERTY_BOX = 'box';
    const PROPERTY_ROOM = 'room';
    const PROPERTY_CITY = 'city';
    const PROPERTY_CITY_ZIP_CODE = 'city_zip_code';
    const PROPERTY_SUBCITY = 'subcity';
    const PROPERTY_SUBCITY_ZIP_CODE = 'subcity_zip_code';
    const PROPERTY_REGION = 'region';

    const TYPE_DOMICILE = 1;
    const TYPE_OFFICE = 2;
    const TYPE_ALTERNATIVE = 3;
    const TYPE_CORRESPONDENCE = 4;
    const TYPE_EXPENSES = 5;
    const TYPE_RESIDENCE = 6;

    /**
     * @return int
     */
    function get_type()
    {
        return $this->get_default_property(self :: PROPERTY_TYPE);
    }

    /**
     * @return string
     */
    function get_country()
    {
        return $this->get_default_property(self :: PROPERTY_COUNTRY);
    }

    /**
     * @return string
     */
    function get_street()
    {
        return $this->get_default_property(self :: PROPERTY_STREET);
    }

    /**
     * @return string
     */
    function get_number()
    {
        return $this->get_default_property(self :: PROPERTY_NUMBER);
    }

    /**
     * @return string
     */
    function get_box()
    {
        return $this->get_default_property(self :: PROPERTY_BOX);
    }

    /**
     * @return string
     */
    function get_room()
    {
        return $this->get_default_property(self :: PROPERTY_ROOM);
    }

    /**
     * @return string
     */
    function get_city()
    {
        return $this->get_default_property(self :: PROPERTY_CITY);
    }

    /**
     * @return string
     */
    function get_city_zip_code()
    {
        return $this->get_default_property(self :: PROPERTY_CITY_ZIP_CODE);
    }

    /**
     * @return string
     */
    function get_subcity()
    {
        return $this->get_default_property(self :: PROPERTY_SUBCITY);
    }

    /**
     * @return string
     */
    function get_subcity_zip_code()
    {
        return $this->get_default_property(self :: PROPERTY_SUBCITY_ZIP_CODE);
    }

    /**
     * @return string
     */
    function get_region()
    {
        return $this->get_default_property(self :: PROPERTY_REGION);
    }

    /**
     * @param int $type
     */
    function set_type($type)
    {
        $this->set_default_property(self :: PROPERTY_TYPE, $type);
    }

    /**
     * @param string $country
     */
    function set_country($country)
    {
        $this->set_default_property(self :: PROPERTY_COUNTRY, $country);
    }

    /**
     * @param string $street
     */
    function set_street($street)
    {
        $this->set_default_property(self :: PROPERTY_STREET, $street);
    }

    /**
     * @param string $number
     */
    function set_number($number)
    {
        $this->set_default_property(self :: PROPERTY_NUMBER, $number);
    }

    /**
     * @param string $box
     */
    function set_box($box)
    {
        $this->set_default_property(self :: PROPERTY_BOX, $box);
    }

    /**
     * @param string $room
     */
    function set_room($room)
    {
        $this->set_default_property(self :: PROPERTY_ROOM, $room);
    }

    /**
     * @param string $city
     */
    function set_city($city)
    {
        $this->set_default_property(self :: PROPERTY_CITY, $city);
    }

    /**
     * @param string $city_zip_code
     */
    function set_city_zip_code($city_zip_code)
    {
        $this->set_default_property(self :: PROPERTY_CITY_ZIP_CODE, $city_zip_code);
    }

    /**
     * @param string $subcity
     */
    function set_subcity($subcity)
    {
        $this->set_default_property(self :: PROPERTY_SUBCITY, $subcity);
    }

    /**
     * @param string $subcity_zip_code
     */
    function set_subcity_zip_code($subcity_zip_code)
    {
        $this->set_default_property(self :: PROPERTY_SUBCITY_ZIP_CODE, $subcity_zip_code);
    }

    /**
     * @param string $region
     */
    function set_region($region)
    {
        $this->set_default_property(self :: PROPERTY_REGION, $region);
    }

    /**
     * @param multitype:string $extended_property_names
     */
    static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_TYPE;
        $extended_property_names[] = self :: PROPERTY_COUNTRY;
        $extended_property_names[] = self :: PROPERTY_STREET;
        $extended_property_names[] = self :: PROPERTY_NUMBER;
        $extended_property_names[] = self :: PROPERTY_BOX;
        $extended_property_names[] = self :: PROPERTY_ROOM;
        $extended_property_names[] = self :: PROPERTY_CITY;
        $extended_property_names[] = self :: PROPERTY_CITY_ZIP_CODE;
        $extended_property_names[] = self :: PROPERTY_SUBCITY;
        $extended_property_names[] = self :: PROPERTY_SUBCITY_ZIP_CODE;
        $extended_property_names[] = self :: PROPERTY_REGION;

        return parent :: get_default_property_names($extended_property_names);
    }

    /**
     * @return DiscoveryDataManagerInterface
     */
    function get_data_manager()
    {
        return DiscoveryDataManager :: get_instance();
    }
}
?>