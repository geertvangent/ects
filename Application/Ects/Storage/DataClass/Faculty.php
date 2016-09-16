<?php
namespace Ehb\Application\Ects\Storage\DataClass;

use Chamilo\Libraries\Storage\DataClass\DataClass;

/**
 *
 * @package Ehb\Application\Ects\Storage\DataClass
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
class Faculty extends DataClass
{
    const PROPERTY_SOURCE = 'source';
    const PROPERTY_PREVIOUS_ID = 'previous_id';
    const PROPERTY_PREVIOUS_SOURCE = 'previous_source';
    const PROPERTY_YEAR = 'year';
    const PROPERTY_NAME = 'name';

    /**
     *
     * @param string[] $extended_property_names
     * @return string[]
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        return parent::get_default_property_names(
            array(
                self::PROPERTY_SOURCE,
                self::PROPERTY_PREVIOUS_ID,
                self::PROPERTY_PREVIOUS_SOURCE,
                self::PROPERTY_YEAR,
                self::PROPERTY_NAME));
    }

    public static function get_table_name()
    {
        return 'v_discovery_faculty_basic';
    }
}