<?php
namespace application\discovery\module\career\implementation\bamaflex;

use common\libraries\Utilities;

/**
 * application.discovery.module.career.implementation.bamaflex.discovery
 * 
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
    // NL : Verzaakt
    const TYPE_REFUSED = 8;
    // NL : AVO verzaakt
    const TYPE_PREVIOUS_REFUSED = 9;
    // NL : EGO verzaakt
    const TYPE_EXTERNAL_REFUSED = 10;
    // NL : Vrijstelling
    const TYPE_EXEMPTION = 11;
    // NL : DeelVrijstelling
    const TYPE_PARTIAL_EXEMPTION = 12;
    // NL : Ontheffing
    const TYPE_RELEASE = 13;
    // NL : Uitgeschreven ontheffing
    const TYPE_RELEASE_STRUCK = 14;
    // NL : Uitgeschreven deelvrijstelling
    const TYPE_PARTIAL_EXEMPTION_STRUCK = 15;
    // NL : Uitgeschreven vrijstelling
    const TYPE_EXEMPTION_STRUCK = 16;
    // NL : Uitgeschreven EGO
    const TYPE_EXTERNAL_STRUCK = 17;
    const PROGRAMME_TYPE_SIMPLE = 1;
    const PROGRAMME_TYPE_COMPLEX = 2;
    const PROGRAMME_TYPE_PART = 4;

    /**
     * Get the default properties
     * 
     * @param multitype:string $extended_property_names
     * @return multitype:string The property names.
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_TRAJECTORY_PART;
        $extended_property_names[] = self :: PROPERTY_CREDITS;
        $extended_property_names[] = self :: PROPERTY_WEIGHT;
        $extended_property_names[] = self :: PROPERTY_ENROLLMENT_ID;
        $extended_property_names[] = self :: PROPERTY_TYPE;
        $extended_property_names[] = self :: PROPERTY_PROGRAMME_ID;
        $extended_property_names[] = self :: PROPERTY_PARENT_PROGRAMME_ID;
        $extended_property_names[] = self :: PROPERTY_SOURCE;
        $extended_property_names[] = self :: PROPERTY_PROGRAMME_TYPE;
        
        return parent :: get_default_property_names($extended_property_names);
    }

    public static function get_types_for_total_credits()
    {
        return array(
            self :: TYPE_NORMAL, 
            self :: TYPE_PREVIOUS, 
            self :: TYPE_EXTERNAL, 
            self :: TYPE_CREDIT_HISTORY, 
            self :: TYPE_EXCHANGE, 
            self :: TYPE_EXEMPTION, 
            self :: TYPE_PARTIAL_EXEMPTION);
    }

    /**
     * Get the data class data manager
     * 
     * @return DataManagerInterface
     */
    public function get_data_manager()
    {
        // return DataManager :: get_instance();
    }

    /**
     * Returns the source of this Course.
     * 
     * @return int The source.
     */
    public function get_source()
    {
        return $this->get_default_property(self :: PROPERTY_SOURCE);
    }

    /**
     * Sets the source of this Course.
     * 
     * @param int $source
     */
    public function set_source($source)
    {
        $this->set_default_property(self :: PROPERTY_SOURCE, $source);
    }

    /**
     * Returns the trajectory_part of this Course.
     * 
     * @return string The trajectory_part.
     */
    public function get_trajectory_part()
    {
        return $this->get_default_property(self :: PROPERTY_TRAJECTORY_PART);
    }

    /**
     * Sets the trajectory_part of this Course.
     * 
     * @param string $trajectory_part
     */
    public function set_trajectory_part($trajectory_part)
    {
        $this->set_default_property(self :: PROPERTY_TRAJECTORY_PART, $trajectory_part);
    }

    /**
     * Returns the credits of this Course.
     * 
     * @return int The credits.
     */
    public function get_credits()
    {
        return $this->get_default_property(self :: PROPERTY_CREDITS);
    }

    /**
     * Sets the credits of this Course.
     * 
     * @param int $credits
     */
    public function set_credits($credits)
    {
        $this->set_default_property(self :: PROPERTY_CREDITS, $credits);
    }

    /**
     * Returns the weight of this Course.
     * 
     * @return int The weight.
     */
    public function get_weight()
    {
        return $this->get_default_property(self :: PROPERTY_WEIGHT);
    }

    /**
     * Sets the weight of this Course.
     * 
     * @param int $weight
     */
    public function set_weight($weight)
    {
        $this->set_default_property(self :: PROPERTY_WEIGHT, $weight);
    }

    /**
     * Returns the enrollment id of this Course.
     * 
     * @return int The enrollment id.
     */
    public function get_enrollment_id()
    {
        return $this->get_default_property(self :: PROPERTY_ENROLLMENT_ID);
    }

    /**
     * Sets the enrollment id of this Course.
     * 
     * @param int $enrollment_id
     */
    public function set_enrollment_id($enrollment_id)
    {
        $this->set_default_property(self :: PROPERTY_ENROLLMENT_ID, $enrollment_id);
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
            case self :: TYPE_REFUSED :
                return 'Refused';
                break;
            case self :: TYPE_PREVIOUS_REFUSED :
                return 'PreviousRefused';
                break;
            case self :: TYPE_EXTERNAL_REFUSED :
                return 'ExternalRefused';
                break;
            case self :: TYPE_EXEMPTION :
                return 'Exemption';
                break;
            case self :: TYPE_PARTIAL_EXEMPTION :
                return 'PartialExemption';
                break;
            case self :: TYPE_RELEASE :
                return 'Release';
                break;
            case self :: TYPE_RELEASE_STRUCK :
                return 'ReleaseStruck';
                break;
            case self :: TYPE_PARTIAL_EXEMPTION_STRUCK :
                return 'PartialExemptionStruck';
                break;
            case self :: TYPE_EXEMPTION_STRUCK :
                return 'ExemptionStruck';
                break;
            case self :: TYPE_EXTERNAL_STRUCK :
                return 'ExternalStruck';
                break;
        }
    }

    /**
     *
     * @return multitype:string
     */
    public static function get_types()
    {
        return array(
            self :: TYPE_NORMAL, 
            self :: TYPE_PREVIOUS, 
            self :: TYPE_EXTERNAL, 
            self :: TYPE_CREDIT_HISTORY, 
            self :: TYPE_STRUCK, 
            self :: TYPE_EXCHANGE, 
            self :: TYPE_CREDIT_HISTORY_INACTIVE, 
            self :: TYPE_REFUSED, 
            self :: TYPE_PREVIOUS_REFUSED, 
            self :: TYPE_EXTERNAL_REFUSED, 
            self :: TYPE_EXEMPTION, 
            self :: TYPE_PARTIAL_EXEMPTION, 
            self :: TYPE_RELEASE, 
            self :: TYPE_RELEASE_STRUCK, 
            self :: TYPE_PARTIAL_EXEMPTION_STRUCK, 
            self :: TYPE_EXEMPTION_STRUCK, 
            self :: TYPE_EXTERNAL_STRUCK);
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
     * Returns the programme_id of this Course.
     * 
     * @return int The programme_id.
     */
    public function get_programme_id()
    {
        return $this->get_default_property(self :: PROPERTY_PROGRAMME_ID);
    }

    /**
     * Sets the programme_id of this Course.
     * 
     * @param int $programme_id
     */
    public function set_programme_id($programme_id)
    {
        $this->set_default_property(self :: PROPERTY_PROGRAMME_ID, $programme_id);
    }

    /**
     * Returns the parent_programme_id of this Course.
     * 
     * @return int The parent_programme_id.
     */
    public function get_parent_programme_id()
    {
        return $this->get_default_property(self :: PROPERTY_PARENT_PROGRAMME_ID);
    }

    /**
     * Sets the parent_programme_id of this Course.
     * 
     * @param int $parent_programme_id
     */
    public function set_parent_programme_id($parent_programme_id)
    {
        $this->set_default_property(self :: PROPERTY_PARENT_PROGRAMME_ID, $parent_programme_id);
    }

    /**
     *
     * @return string The table name of the data class
     */
    public static function get_table_name()
    {
        return Utilities :: get_classname_from_namespace(self :: CLASS_NAME, true);
    }

    public function get_mark_by_moment_id($moment_id)
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

    /**
     *
     * @return string
     */
    public static function programme_type_string($programme_type)
    {
        switch ($programme_type)
        {
            case self :: PROGRAMME_TYPE_SIMPLE :
                return 'SimpleCourse';
                break;
            case self :: PROGRAMME_TYPE_COMPLEX :
                return 'ComplexCourse';
                break;
            case self :: PROGRAMME_TYPE_PART :
                return 'ComplexCoursePart';
                break;
        }
    }
}
