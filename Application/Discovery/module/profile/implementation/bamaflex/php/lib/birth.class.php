<?php
namespace application\discovery\module\profile\implementation\bamaflex;

use common\libraries\Utilities;
use common\libraries\Translation;
use common\libraries\DatetimeUtilities;
use common\libraries\DataClass;

class Birth extends DataClass
{
    const CLASS_NAME = __CLASS__;
    const PROPERTY_DATE = 'date';
    const PROPERTY_PLACE = 'place';
    const PROPERTY_COUNTRY = 'country';

    /**
     *
     * @return int
     */
    public function get_date()
    {
        return $this->get_default_property(self :: PROPERTY_DATE);
    }

    public function get_formatted_date($format = null)
    {
        if (! $format)
        {
            $format = Translation :: get('DateFormatShort', null, Utilities :: COMMON_LIBRARIES);
        }
        
        return DatetimeUtilities :: format_locale_date($format, $this->get_date());
    }

    /**
     *
     * @return string
     */
    public function get_place()
    {
        return $this->get_default_property(self :: PROPERTY_PLACE);
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
     * @param int $date
     */
    public function set_date($date)
    {
        $this->set_default_property(self :: PROPERTY_DATE, $date);
    }

    /**
     *
     * @param string $place
     */
    public function set_place($place)
    {
        $this->set_default_property(self :: PROPERTY_PLACE, $place);
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
     * @return boolean
     */
    public function has_date()
    {
        return ($this->get_date() && $this->get_date() != 0 ? true : false);
    }

    /**
     *
     * @return boolean
     */
    public function has_location()
    {
        return ($this->get_place() || $this->get_country() ? true : false);
    }

    /**
     *
     * @return string
     */
    public function get_location()
    {
        if ($this->get_place())
        {
            $location[] = $this->get_place();
        }
        
        if ($this->get_country())
        {
            $location[] = '(' . $this->get_country() . ')';
        }
        
        return implode(' ', $location);
    }

    /**
     *
     * @param multitype:string $extended_property_names
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_DATE;
        $extended_property_names[] = self :: PROPERTY_PLACE;
        $extended_property_names[] = self :: PROPERTY_COUNTRY;
        
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

    /**
     *
     * @return string
     */
    public function __toString()
    {
        $birth_date = $this->get_formatted_date();
        
        if ($this->has_location())
        {
            return Translation :: get('BornIn', array('DATE' => $birth_date, 'PLACE' => $this->get_location()));
        }
        else
        {
            return $birth_date;
        }
    }
}
