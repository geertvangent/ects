<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\DataClass;

/**
 *
 * @package Ehb\Application\Ects\Storage\DataClass
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
class LocationActivity extends Activity
{
    const PROPERTY_LOCATION_ID = 'location_id';
    const PROPERTY_LOCATION_CODE = 'location_code';
    const PROPERTY_LOCATION_NAME = 'location_name';

    /**
     *
     * @param string[] $extended_property_names
     * @return string[]
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self::PROPERTY_LOCATION_ID;
        $extended_property_names[] = self::PROPERTY_LOCATION_CODE;
        $extended_property_names[] = self::PROPERTY_LOCATION_NAME;

        return parent::get_default_property_names($extended_property_names);
    }

    /**
     *
     * @return string
     */
    public function getLocationId()
    {
        return $this->get_default_property(self::PROPERTY_LOCATION_ID);
    }

    /**
     *
     * @param string $location_id
     */
    public function setLocationId($location_id)
    {
        $this->set_default_property(self::PROPERTY_LOCATION_ID, $location_id);
    }

    /**
     *
     * @return string
     */
    public function getLocationCode()
    {
        return $this->get_default_property(self::PROPERTY_LOCATION_CODE);
    }

    /**
     *
     * @param string $location_code
     */
    public function setLocationCode($location_code)
    {
        $this->set_default_property(self::PROPERTY_LOCATION_CODE, $location_code);
    }

    /**
     *
     * @return string
     */
    public function getLocationName()
    {
        return $this->get_default_property(self::PROPERTY_LOCATION_NAME);
    }

    /**
     *
     * @param string $location_name
     */
    public function setLocationName($location_name)
    {
        $this->set_default_property(self::PROPERTY_LOCATION_NAME, $location_name);
    }

    /**
     *
     * @return string
     */
    public static function get_table_name()
    {
        return 'v_syllabus_1617_event_location';
    }
}