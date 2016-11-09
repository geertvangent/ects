<?php
namespace Ehb\Application\Ects\Storage\DataClass;

use Ehb\Libraries\Storage\DataClass\AdministrationDataClass;

/**
 *
 * @package Ehb\Application\Ects\Storage\DataClass
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 * @author Magali Gillard <magali.gillard@ehb.be>
 */
class SubTrajectory extends AdministrationDataClass
{
    const PROPERTY_SOURCE = 'source';
    const PROPERTY_NAME = 'name';
    const PROPERTY_TRAJECTORY_ID = 'trajectory_id';
    const PROPERTY_SORT = 'sort';
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
                self::PROPERTY_NAME, 
                self::PROPERTY_TRAJECTORY_ID, 
                self::PROPERTY_SORT, 
                self::PROPERTY_INVISIBLE));
    }

    /**
     *
     * @return integer
     */
    public function getTrajectoryId()
    {
        return $this->get_default_property(self::PROPERTY_TRAJECTORY_ID);
    }

    /**
     *
     * @return string
     */
    public static function get_table_name()
    {
        return 'v_discovery_training_sub_trajectory_basic';
    }
}