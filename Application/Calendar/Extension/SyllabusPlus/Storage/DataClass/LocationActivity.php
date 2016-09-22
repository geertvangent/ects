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
        return parent::get_default_property_names(
            array(self::PROPERTY_LOCATION_ID, self::PROPERTY_LOCATION_CODE, self::PROPERTY_LOCATION_NAME));
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