<?php
namespace application\discovery\module\profile\implementation\bamaflex;

use application\discovery\DiscoveryDataManager;

use common\libraries\DataClass;

class Birth extends DataClass
{
    const CLASS_NAME = __CLASS__;

    const PROPERTY_DATE = 'date';
    const PROPERTY_PLACE = 'place';
    const PROPERTY_COUNTRY = 'country';

    /**
     * @return int
     */
    function get_date()
    {
        return $this->get_default_property(self :: PROPERTY_DATE);
    }

    /**
     * @return string
     */
    function get_place()
    {
        return $this->get_default_property(self :: PROPERTY_PLACE);
    }

    /**
     * @return string
     */
    function get_country()
    {
        return $this->get_default_property(self :: PROPERTY_COUNTRY);
    }

    /**
     * @param int $date
     */
    function set_date($date)
    {
        $this->set_default_property(self :: PROPERTY_DATE, $date);
    }

    /**
     * @param string $place
     */
    function set_place($place)
    {
        $this->set_default_property(self :: PROPERTY_PLACE, $place);
    }

    /**
     * @param string $country
     */
    function set_country($country)
    {
        $this->set_default_property(self :: PROPERTY_COUNTRY, $country);
    }

    /**
     * @param multitype:string $extended_property_names
     */
    static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_DATE;
        $extended_property_names[] = self :: PROPERTY_PLACE;
        $extended_property_names[] = self :: PROPERTY_COUNTRY;

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