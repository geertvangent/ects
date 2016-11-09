<?php
namespace Ehb\Application\Ects\Storage\DataClass;

use Ehb\Libraries\Storage\DataClass\AdministrationDataClass;

/**
 *
 * @package Ehb\Application\Ects\Storage\DataClass
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
class Training extends AdministrationDataClass
{
    const PROPERTY_SOURCE = 'source';
    const PROPERTY_PREVIOUS_ID = 'previous_id';
    const PROPERTY_PREVIOUS_SOURCE = 'previous_source';
    const PROPERTY_YEAR = 'year';
    const PROPERTY_CODE = 'code';
    const PROPERTY_NAME = 'name';
    const PROPERTY_START_DATE = 'start_date';
    const PROPERTY_END_DATE = 'end_date';
    const PROPERTY_DOMAIN_ID = 'domain_id';
    const PROPERTY_DOMAIN = 'domain';
    const PROPERTY_GROUP_ID = 'group_id';
    const PROPERTY_GROUP = 'group';
    const PROPERTY_TYPE_ID = 'type_id';
    const PROPERTY_TYPE = 'type';
    const PROPERTY_BAMA_TYPE = 'bama_type';
    const PROPERTY_GOALS = 'goals';
    const PROPERTY_CREDITS = 'credits';
    const PROPERTY_FACULTY_ID = 'faculty_id';
    const PROPERTY_FACULTY = 'faculty';
    const PROPERTY_INVISIBLE = 'invisible';

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
                self::PROPERTY_CODE,
                self::PROPERTY_NAME,
                self::PROPERTY_START_DATE,
                self::PROPERTY_END_DATE,
                self::PROPERTY_DOMAIN_ID,
                self::PROPERTY_DOMAIN,
                self::PROPERTY_GROUP_ID,
                self::PROPERTY_GROUP,
                self::PROPERTY_TYPE_ID,
                self::PROPERTY_TYPE,
                self::PROPERTY_BAMA_TYPE,
                self::PROPERTY_GOALS,
                self::PROPERTY_CREDITS,
                self::PROPERTY_FACULTY_ID,
                self::PROPERTY_FACULTY,
                self::PROPERTY_INVISIBLE));
    }

    public static function get_table_name()
    {
        return 'v_discovery_training_basic';
    }
}