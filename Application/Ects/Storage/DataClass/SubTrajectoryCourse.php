<?php
namespace Ehb\Application\Ects\Storage\DataClass;

use Ehb\Libraries\Storage\DataClass\AdministrationDataClass;

/**
 *
 * @package Ehb\Application\Ects\Storage\DataClass
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
class SubTrajectoryCourse extends AdministrationDataClass
{
    const PROPERTY_SOURCE = 'source';
    const PROPERTY_NAME = 'name';
    const PROPERTY_TRAJECTORY_PART = 'trajectory_part';
    const PROPERTY_CREDITS = 'credits';
    const PROPERTY_SUB_TRAJECTORY_ID = 'sub_trajectory_id';
    const PROPERTY_PROGRAMME_ID = 'programme_id';
    const PROPERTY_PARENT_PROGRAMME_ID = 'parent_programme_id';
    const PROPERTY_APPROVED = 'approved';
    const PROPERTY_COURSE_PARTS = 'course_parts';

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
                self::PROPERTY_NAME, 
                self::PROPERTY_TRAJECTORY_PART, 
                self::PROPERTY_CREDITS, 
                self::PROPERTY_SUB_TRAJECTORY_ID, 
                self::PROPERTY_PROGRAMME_ID, 
                self::PROPERTY_PARENT_PROGRAMME_ID, 
                self::PROPERTY_APPROVED));
    }

    /**
     *
     * @return string
     */
    public static function get_table_name()
    {
        return 'v_discovery_training_info_sub_trajectory_course_basic';
    }
}