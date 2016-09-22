<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Storage\DataClass;

/**
 *
 * @package Ehb\Application\Ects\Storage\DataClass
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
class GroupActivity extends Activity
{
    const PROPERTY_GROUP_ID = 'group_id';
    const PROPERTY_GROUP_NAME = 'group_name';

    /**
     *
     * @param string[] $extended_property_names
     * @return string[]
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        return parent::get_default_property_names(array(self::PROPERTY_GROUP_ID, self::PROPERTY_GROUP_NAME));
    }

    /**
     *
     * @return string
     */
    public static function get_table_name()
    {
        return 'v_syllabus_1617_event_group';
    }
}