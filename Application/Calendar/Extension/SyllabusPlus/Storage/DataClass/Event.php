<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\DataClass;

use Chamilo\Libraries\Storage\DataClass\DataClass;

/**
 *
 * @package Ehb\Application\Ects\Storage\DataClass
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
abstract class Event extends DataClass
{
    const PROPERTY_START_TIME = 'start_time';
    const PROPERTY_END_TIME = 'end_time';

    /**
     *
     * @param string[] $extended_property_names
     * @return string[]
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        return parent::get_default_property_names(array(self::PROPERTY_START_TIME, self::PROPERTY_END_TIME));
    }
}