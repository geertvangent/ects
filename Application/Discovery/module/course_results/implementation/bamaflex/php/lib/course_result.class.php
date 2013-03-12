<?php
namespace application\discovery\module\course_results\implementation\bamaflex;


use common\libraries\Utilities;

/**
 * application.discovery.module.course_results.implementation.bamaflex.discovery
 *
 * @author Hans De Bisschop
 */
class CourseResult extends \application\discovery\module\course_results\CourseResult
{
    const CLASS_NAME = __CLASS__;

    /**
     * CourseResults properties
     */
    const PROPERTY_TYPE = 'type';
    const PROPERTY_TRAJECTORY_TYPE = 'trajectory_type';
    const TRAJECTORY_TYPE_TEMPLATE = 1;
    const TRAJECTORY_TYPE_PERSONAL = 2;
    const TRAJECTORY_TYPE_INDIVIDUAL = 3;
    const TRAJECTORY_TYPE_UNKNOWN = 4;
    const TYPE_NORMAL = 1;
    // NL: AVO
    const TYPE_PREVIOUS = 2;

    /**
     * Get the default properties
     *
     * @param multitype:string $extended_property_names
     * @return multitype:string The property names.
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_TRAJECTORY_TYPE;
        $extended_property_names[] = self :: PROPERTY_TYPE;

        return parent :: get_default_property_names($extended_property_names);
    }

    /**
     * Get the data class data manager
     *
     * @return DataManagerInterface
     */
    public function get_data_manager()
    {
//         return DataManager :: get_instance();
    }

    /**
     * Returns the trajectory_part of this Course.
     *
     * @return string The trajectory_part.
     */
    public function get_trajectory_type()
    {
        return $this->get_default_property(self :: PROPERTY_TRAJECTORY_TYPE);
    }

    /**
     * Sets the trajectory_part of this Course.
     *
     * @param string $trajectory_part
     */
    public function set_trajectory_type($trajectory_type)
    {
        $this->set_default_property(self :: PROPERTY_TRAJECTORY_TYPE, $trajectory_type);
    }

    /**
     *
     * @return string
     */
    public function get_trajectory_type_string()
    {
        switch ($this->get_trajectory_type())
        {
            case self :: TRAJECTORY_TYPE_TEMPLATE :
                return 'Template';
                break;
            case self :: TRAJECTORY_TYPE_PERSONAL :
                return 'Personal';
                break;
            case self :: TRAJECTORY_TYPE_INDIVIDUAL :
                return 'Individual';
                break;
            case self :: TRAJECTORY_TYPE_UNKNOWN :

                return 'Unknown';
                break;
        }
    }

    /**
     * Returns the type of this Course.
     *
     * @return int The type.
     */
    public function get_type()
    {
        return $this->get_default_property(self :: PROPERTY_TYPE);
    }

    /**
     * Sets the type of this Course.
     *
     * @param int $type
     */
    public function set_type($type)
    {
        $this->set_default_property(self :: PROPERTY_TYPE, $type);
    }

    /**
     *
     * @return string
     */
    public function get_type_string()
    {
        return self :: type_string($this->get_type());
    }

    /**
     *
     * @return string
     */
    public static function type_string($type)
    {
        switch ($type)
        {
            case self :: TYPE_NORMAL :
                return 'Normal';
                break;
            case self :: TYPE_PREVIOUS :
                return 'Previous';
                break;
        }
    }

    /**
     *
     * @return multitype:string
     */
    public static function get_types()
    {
        return array(self :: TYPE_NORMAL, self :: TYPE_PREVIOUS);
    }

    /**
     *
     * @return boolean
     */
    public function is_special_type()
    {
        return ($this->get_type() != self :: TYPE_NORMAL);
    }

    /**
     *
     * @return string The table name of the data class
     */
    public static function get_table_name()
    {
        return Utilities :: get_classname_from_namespace(self :: CLASS_NAME, true);
    }
}
