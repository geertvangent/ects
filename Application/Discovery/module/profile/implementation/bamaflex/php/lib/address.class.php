<?php
namespace application\discovery\module\profile\implementation\bamaflex;

use libraries\platform\translation\Translation;
use libraries\storage\data_class\DataClass;

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
     *
     * @return int
     */
    public function get_type()
    {
        return $this->get_default_property(self :: PROPERTY_TYPE);
    }

    /**
     *
     * @return string
     */
    public function get_type_string()
    {
        switch ($this->get_type())
        {
            case self :: TYPE_DOMICILE :
                return 'Domicile';
                break;
            case self :: TYPE_OFFICE :
                return 'Office';
                break;
            case self :: TYPE_ALTERNATIVE :
                return 'Alternative';
                break;
            case self :: TYPE_CORRESPONDENCE :
                return 'Correspondence';
                break;
            case self :: TYPE_EXPENSES :
                return 'Expenses';
                break;
            case self :: TYPE_RESIDENCE :
                return 'Residence';
                break;
        }
    }

    /**
     *
     * @return string
     */
    public function get_country()
    {
        return $this->get_default_property(self :: PROPERTY_COUNTRY);
    }

    /**
     *
     * @return string
     */
    public function get_street()
    {
        return $this->get_default_property(self :: PROPERTY_STREET);
    }

    /**
     *
     * @return string
     */
    public function get_number()
    {
        return $this->get_default_property(self :: PROPERTY_NUMBER);
    }

    /**
     *
     * @return string
     */
    public function get_box()
    {
        return $this->get_default_property(self :: PROPERTY_BOX);
    }

    /**
     *
     * @return string
     */
    public function get_room()
    {
        return $this->get_default_property(self :: PROPERTY_ROOM);
    }

    /**
     *
     * @return string
     */
    public function get_city()
    {
        return $this->get_default_property(self :: PROPERTY_CITY);
    }

    /**
     *
     * @return string
     */
    public function get_city_zip_code()
    {
        return $this->get_default_property(self :: PROPERTY_CITY_ZIP_CODE);
    }

    /**
     *
     * @return string
     */
    public function get_subcity()
    {
        return $this->get_default_property(self :: PROPERTY_SUBCITY);
    }

    /**
     *
     * @return string
     */
    public function get_subcity_zip_code()
    {
        return $this->get_default_property(self :: PROPERTY_SUBCITY_ZIP_CODE);
    }

    public function get_unified_city()
    {
        if (! $this->get_city() && $this->get_subcity())
        {
            return $this->get_subcity();
        }
        elseif ($this->get_city() && ! $this->get_subcity())
        {
            return $this->get_city();
        }
        elseif ($this->get_city() && $this->get_subcity())
        {
            if ($this->get_city() != $this->get_subcity())
            {
                return $this->get_subcity() . ' (' . $this->get_city() . ')';
            }
            else
            {
                return $this->get_city();
            }
        }
        else
        {
            return '-';
        }
    }

    public function get_unified_city_zip_code()
    {
        if (! $this->get_city_zip_code() && $this->get_subcity_zip_code())
        {
            return $this->get_subcity_zip_code();
        }
        elseif ($this->get_city_zip_code() && ! $this->get_subcity_zip_code())
        {
            return $this->get_city_zip_code();
        }
        elseif ($this->get_city_zip_code() && $this->get_subcity_zip_code())
        {
            return $this->get_city_zip_code();
        }
        else
        {
            return '-';
        }
    }

    /**
     *
     * @return string
     */
    public function get_region()
    {
        return $this->get_default_property(self :: PROPERTY_REGION);
    }

    /**
     *
     * @param int $type
     */
    public function set_type($type)
    {
        $this->set_default_property(self :: PROPERTY_TYPE, $type);
    }

    /**
     *
     * @param string $country
     */
    public function set_country($country)
    {
        $this->set_default_property(self :: PROPERTY_COUNTRY, $country);
    }

    /**
     *
     * @param string $street
     */
    public function set_street($street)
    {
        $this->set_default_property(self :: PROPERTY_STREET, $street);
    }

    /**
     *
     * @param string $number
     */
    public function set_number($number)
    {
        $this->set_default_property(self :: PROPERTY_NUMBER, $number);
    }

    /**
     *
     * @param string $box
     */
    public function set_box($box)
    {
        $this->set_default_property(self :: PROPERTY_BOX, $box);
    }

    /**
     *
     * @param string $room
     */
    public function set_room($room)
    {
        $this->set_default_property(self :: PROPERTY_ROOM, $room);
    }

    /**
     *
     * @param string $city
     */
    public function set_city($city)
    {
        $this->set_default_property(self :: PROPERTY_CITY, $city);
    }

    /**
     *
     * @param string $city_zip_code
     */
    public function set_city_zip_code($city_zip_code)
    {
        $this->set_default_property(self :: PROPERTY_CITY_ZIP_CODE, $city_zip_code);
    }

    /**
     *
     * @param string $subcity
     */
    public function set_subcity($subcity)
    {
        $this->set_default_property(self :: PROPERTY_SUBCITY, $subcity);
    }

    /**
     *
     * @param string $subcity_zip_code
     */
    public function set_subcity_zip_code($subcity_zip_code)
    {
        $this->set_default_property(self :: PROPERTY_SUBCITY_ZIP_CODE, $subcity_zip_code);
    }

    /**
     *
     * @param string $region
     */
    public function set_region($region)
    {
        $this->set_default_property(self :: PROPERTY_REGION, $region);
    }

    /**
     *
     * @param multitype:string $extended_property_names
     */
    public static function get_default_property_names($extended_property_names = array())
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
     *
     * @return DataManagerInterface
     */
    public function get_data_manager()
    {
        // return DataManager :: get_instance();
    }

    public function __toString()
    {
        $address = array();
        
        // Street, street number, box and room
        if ($this->get_street())
        {
            $street = array();
            $street[] = $this->get_street();
            
            if ($this->get_number())
            {
                $street[] = $this->get_number();
            }
            
            if ($this->get_box())
            {
                $street[] = Translation :: get('Box') . ' ' . $this->get_box();
            }
            
            if ($this->get_room())
            {
                $street[] = Translation :: get('Room') . ' ' . $this->get_room();
            }
            
            $address[] = implode(' ', $street);
        }
        
        // City and zip code
        $address[] = $this->get_unified_city_zip_code() . ' ' . $this->get_unified_city();
        
        if ($this->get_region())
        {
            $address[] = $this->get_region();
        }
        
        if ($this->get_country())
        {
            $address[] = $this->get_country();
        }
        
        return implode('<br />', $address);
    }
}
