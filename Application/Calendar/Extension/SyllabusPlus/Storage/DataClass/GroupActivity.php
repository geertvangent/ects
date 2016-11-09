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
        $extended_property_names[] = self::PROPERTY_GROUP_ID;
        $extended_property_names[] = self::PROPERTY_GROUP_NAME;
        
        return parent::get_default_property_names($extended_property_names);
    }

    /**
     *
     * @return string
     */
    public function getGroupId()
    {
        return $this->get_default_property(self::PROPERTY_GROUP_ID);
    }

    /**
     *
     * @param string $group_id
     */
    public function setGroupId($group_id)
    {
        $this->set_default_property(self::PROPERTY_GROUP_ID, $group_id);
    }

    /**
     *
     * @return string
     */
    public function getGroupName()
    {
        return $this->get_default_property(self::PROPERTY_GROUP_NAME);
    }

    /**
     *
     * @param string $group_name
     */
    public function setGroupName($group_name)
    {
        $this->set_default_property(self::PROPERTY_GROUP_NAME, $group_name);
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