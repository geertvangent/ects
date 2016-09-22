<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\DataClass;

use Chamilo\Libraries\Storage\DataClass\DataClass;

/**
 *
 * @package Ehb\Application\Ects\Storage\DataClass
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
abstract class Activity extends DataClass
{
    const PROPERTY_YEAR = 'year';
    const PROPERTY_MODULE_ID = 'module_id';
    const PROPERTY_NAME = 'name';
    const PROPERTY_START_TIME = 'start_time';
    const PROPERTY_END_TIME = 'end_time';
    const PROPERTY_LOCATION = 'location';
    const PROPERTY_TYPE_CODE = 'type_code';
    const PROPERTY_TYPE = 'type';
    const PROPERTY_STUDENT_GROUP = 'student_group';
    const PROPERTY_TEACHER = 'teacher';

    /**
     *
     * @param string[] $extended_property_names
     * @return string[]
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self::PROPERTY_YEAR;
        $extended_property_names[] = self::PROPERTY_MODULE_ID;
        $extended_property_names[] = self::PROPERTY_NAME;
        $extended_property_names[] = self::PROPERTY_START_TIME;
        $extended_property_names[] = self::PROPERTY_END_TIME;
        $extended_property_names[] = self::PROPERTY_LOCATION;
        $extended_property_names[] = self::PROPERTY_TYPE_CODE;
        $extended_property_names[] = self::PROPERTY_TYPE;
        $extended_property_names[] = self::PROPERTY_STUDENT_GROUP;
        $extended_property_names[] = self::PROPERTY_TEACHER;

        return parent::get_default_property_names($extended_property_names);
    }
}