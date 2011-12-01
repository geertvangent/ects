<?php
namespace application\discovery\module\career\implementation\bamaflex;

use application\discovery\DiscoveryDataManager;

use common\libraries\Utilities;
use common\libraries\DataClass;

/**
 * application.discovery.module.career.implementation.bamaflex.discovery
 * @author Hans De Bisschop
 */
class Course extends \application\discovery\module\career\Course
{
    const CLASS_NAME = __CLASS__;
    
    /**
     * Course properties
     */
    const PROPERTY_SOURCE = 'source';
    const PROPERTY_TRAJECTORY_PART = 'trajectory_part';
    const PROPERTY_CREDITS = 'credits';
    const PROPERTY_WEIGHT = 'weight';
    const PROPERTY_ENROLLMENT_ID = 'enrollment_id';
    const PROPERTY_TYPE = 'type';
    const PROPERTY_PROGRAMME_ID = 'programme_id';
    const PROPERTY_PARENT_PROGRAMME_ID = 'parent_programme_id';
    
    const TYPE_NORMAL = 1;
    // NL: AVO
    const TYPE_PREVIOUS = 2;
    // NL: EGO
    const TYPE_EXTERNAL = 3;
    // NL: Credithistoriek - Actief
    const TYPE_CREDIT_HISTORY = 4;
    // NL: Uitgeschreven
    const TYPE_STRUCK = 5;
    // NL : Uitwisseling
    const TYPE_EXCHANGE = 6;
    // NL: Credithistoriek - Inactief
    const TYPE_CREDIT_HISTORY_INACTIVE = 7;

    /**
     * Get the default properties
     * @param multitype:string $extended_property_names
     * @return multitype:string The property names.
     */
    static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_TRAJECTORY_PART;
        $extended_property_names[] = self :: PROPERTY_CREDITS;
        $extended_property_names[] = self :: PROPERTY_WEIGHT;
        $extended_property_names[] = self :: PROPERTY_ENROLLMENT_ID;
        $extended_property_names[] = self :: PROPERTY_TYPE;
        $extended_property_names[] = self :: PROPERTY_PROGRAMME_ID;
        $extended_property_names[] = self :: PROPERTY_PARENT_PROGRAMME_ID;
        $extended_property_names[] = self :: PROPERTY_SOURCE;
        
        return parent :: get_default_property_names($extended_property_names);
    }

    /**
     * Get the data class data manager
     * @return DiscoveryDataManagerInterface
     */
    function get_data_manager()
    {
        return DiscoveryDataManager :: get_instance();
    }

    /**
     * Returns the source of this Course.
     * @return int The source.
     */
    function get_source()
    {
        return $this->get_default_property(self :: PROPERTY_SOURCE);
    }

    /**
     * Sets the source of this Course.
     * @param int $source
     */
    function set_source($source)
    {
        $this->set_default_property(self :: PROPERTY_SOURCE, $source);
    }

    /**
     * Returns the trajectory_part of this Course.
     * @return string The trajectory_part.
     */
    function get_trajectory_part()
    {
        return $this->get_default_property(self :: PROPERTY_TRAJECTORY_PART);
    }

    /**
     * Sets the trajectory_part of this Course.
     * @param string $trajectory_part
     */
    function set_trajectory_part($trajectory_part)
    {
        $this->set_default_property(self :: PROPERTY_TRAJECTORY_PART, $trajectory_part);
    }

    /**
     * Returns the credits of this Course.
     * @return int The credits.
     */
    function get_credits()
    {
        return $this->get_default_property(self :: PROPERTY_CREDITS);
    }

    /**
     * Sets the credits of this Course.
     * @param int $credits
     */
    function set_credits($credits)
    {
        $this->set_default_property(self :: PROPERTY_CREDITS, $credits);
    }

    /**
     * Returns the weight of this Course.
     * @return int The weight.
     */
    function get_weight()
    {
        return $this->get_default_property(self :: PROPERTY_WEIGHT);
    }

    /**
     * Sets the weight of this Course.
     * @param int $weight
     */
    function set_weight($weight)
    {
        $this->set_default_property(self :: PROPERTY_WEIGHT, $weight);
    }

    /**
     * Returns the enrollment id of this Course.
     * @return int The enrollment id.
     */
    function get_enrollment_id()
    {
        return $this->get_default_property(self :: PROPERTY_ENROLLMENT_ID);
    }

    /**
     * Sets the enrollment id of this Course.
     * @param int $enrollment_id
     */
    function set_enrollment_id($enrollment_id)
    {
        $this->set_default_property(self :: PROPERTY_ENROLLMENT_ID, $enrollment_id);
    }

    /**
     * Returns the type of this Course.
     * @return int The type.
     */
    function get_type()
    {
        return $this->get_default_property(self :: PROPERTY_TYPE);
    }

    /**
     * Sets the type of this Course.
     * @param int $type
     */
    function set_type($type)
    {
        $this->set_default_property(self :: PROPERTY_TYPE, $type);
    }

    /**
     * @return string
     */
    function get_type_string()
    {
        return self :: type_string($this->get_type());
    }

    /**
     * @return string
     */
    static function type_string($type)
    {
        switch ($type)
        {
            case self :: TYPE_NORMAL :
                return 'Normal';
                break;
            case self :: TYPE_PREVIOUS :
                return 'Previous';
                break;
            case self :: TYPE_EXTERNAL :
                return 'External';
                break;
            case self :: TYPE_CREDIT_HISTORY :
                return 'CreditHistory';
                break;
            case self :: TYPE_CREDIT_HISTORY_INACTIVE :
                return 'CreditHistoryInactive';
                break;
            case self :: TYPE_STRUCK :
                return 'Struck';
                break;
            case self :: TYPE_EXCHANGE :
                return 'Exchange';
                break;
        }
    }

    /**
     * @return multitype:string
     */
    static function get_types()
    {
        return array(self :: TYPE_NORMAL, self :: TYPE_PREVIOUS, self :: TYPE_EXTERNAL, self :: TYPE_CREDIT_HISTORY, 
                self :: TYPE_STRUCK, self :: TYPE_EXCHANGE, self :: TYPE_CREDIT_HISTORY_INACTIVE);
    }

    /**
     * @return boolean
     */
    function is_special_type()
    {
        return ($this->get_type() != self :: TYPE_NORMAL);
    }

    /**
     * Returns the programme_id of this Course.
     * @return int The programme_id.
     */
    function get_programme_id()
    {
        return $this->get_default_property(self :: PROPERTY_PROGRAMME_ID);
    }

    /**
     * Sets the programme_id of this Course.
     * @param int $programme_id
     */
    function set_programme_id($programme_id)
    {
        $this->set_default_property(self :: PROPERTY_PROGRAMME_ID, $programme_id);
    }

    /**
     * Returns the parent_programme_id of this Course.
     * @return int The parent_programme_id.
     */
    function get_parent_programme_id()
    {
        return $this->get_default_property(self :: PROPERTY_PARENT_PROGRAMME_ID);
    }

    /**
     * Sets the parent_programme_id of this Course.
     * @param int $parent_programme_id
     */
    function set_parent_programme_id($parent_programme_id)
    {
        $this->set_default_property(self :: PROPERTY_PARENT_PROGRAMME_ID, $parent_programme_id);
    }

    /**
     * @return string The table name of the data class
     */
    static function get_table_name()
    {
        return Utilities :: get_classname_from_namespace(self :: CLASS_NAME, true);
    }

    function get_mark_by_moment_id($moment_id)
    {
        
        foreach ($this->get_marks() as $mark)
        {
            if ($mark->get_moment() == $moment_id)
            {
                return $mark;
            }
        }
        
        return Mark :: factory($moment_id);
    }
}
