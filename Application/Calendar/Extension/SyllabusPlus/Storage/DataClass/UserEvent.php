<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\DataClass;

use Chamilo\Libraries\Storage\DataClass\DataClass;

/**
 *
 * @package Ehb\Application\Ects\Storage\DataClass
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
abstract class UserEvent extends DataClass
{
    const PROPERTY_YEAR = 'year';
    const PROPERTY_MODULE_ID = 'module_id';
    const PROPERTY_NAME = 'name';
    const PROPERTY_LOCATION = 'location';
    const PROPERTY_TYPE_CODE = 'type_code';
    const PROPERTY_TYPE = 'type';
    const PROPERTY_STUDENT_GROUP = 'student_group';
    const PROPERTY_TEACHER = 'teacher';
    const PROPERTY_PERSON_ID = 'person_id';

    /**
     *
     * @param string[] $extended_property_names
     * @return string[]
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        return parent::get_default_property_names(
            array(
                self::PROPERTY_YEAR,
                self::PROPERTY_MODULE_ID,
                self::PROPERTY_NAME,
                self::PROPERTY_LOCATION,
                self::PROPERTY_TYPE_CODE,
                self::PROPERTY_TYPE,
                self::PROPERTY_STUDENT_GROUP,
                self::PROPERTY_TEACHER,
                self::PROPERTY_PERSON_ID));
    }
}