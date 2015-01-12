<?php
namespace Application\Discovery\module\student_year\implementation\bamaflex;

use libraries\utilities\Utilities;

/**
 * application.discovery.module.student_year.implementation.bamaflex
 * 
 * @author Hans De Bisschop
 */
class StudentYear extends \application\discovery\module\student_year\StudentYear
{
    const CLASS_NAME = __CLASS__;
    
    /**
     *
     * @var string
     */
    const PROPERTY_SOURCE = 'source';
    
    /**
     *
     * @var integer
     */
    const PROPERTY_REDUCED_REGISTRATION_FEE_ID = 'reduced_registration_fee_id';
    const REDUCED_REGISTRATION_FEE_NO = 1;
    const REDUCED_REGISTRATION_FEE_YES = 2;
    const REDUCED_REGISTRATION_FEE_ALMOST = 3;
    const REDUCED_REGISTRATION_FEE_PENDING = 4;
    const REDUCED_REGISTRATION_FEE_REFUSED = 5;

    /**
     * Get the default properties
     * 
     * @param multitype:string $extended_property_names
     * @return multitype:string The property names.
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_SOURCE;
        $extended_property_names[] = self :: PROPERTY_REDUCED_REGISTRATION_FEE_ID;
        
        return parent :: get_default_property_names($extended_property_names);
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
     * Returns the source of this StudentYear.
     * 
     * @return string The source.
     */
    public function get_source()
    {
        return $this->get_default_property(self :: PROPERTY_SOURCE);
    }

    /**
     * Sets the source of this StudentYear.
     * 
     * @param string $source
     */
    public function set_source($source)
    {
        $this->set_default_property(self :: PROPERTY_SOURCE, $source);
    }

    /**
     * Returns the reduced_registration_fee_id of this StudentYear.
     * 
     * @return integer The reduced_registration_fee_id.
     */
    public function get_reduced_registration_fee_id()
    {
        return $this->get_default_property(self :: PROPERTY_REDUCED_REGISTRATION_FEE_ID);
    }

    /**
     * Sets the reduced_registration_fee_id of this StudentYear.
     * 
     * @param integer $reduced_registration_fee_id
     */
    public function set_reduced_registration_fee_id($reduced_registration_fee_id)
    {
        $this->set_default_property(self :: PROPERTY_REDUCED_REGISTRATION_FEE_ID, $reduced_registration_fee_id);
    }

    /**
     *
     * @return string
     */
    public function get_reduced_registration_fee_string()
    {
        return self :: reduced_registration_fee_string($this->get_reduced_registration_fee_id());
    }

    /**
     *
     * @return string
     */
    public static function reduced_registration_fee_string($reduced_registration_fee)
    {
        switch ($reduced_registration_fee)
        {
            case self :: REDUCED_REGISTRATION_FEE_NO :
                return 'ReducedRegistrationFeeNo';
                break;
            case self :: REDUCED_REGISTRATION_FEE_YES :
                return 'ReducedRegistrationFeeYes';
                break;
            case self :: REDUCED_REGISTRATION_FEE_ALMOST :
                return 'ReducedRegistrationFeeAlmost';
                break;
            case self :: REDUCED_REGISTRATION_FEE_PENDING :
                return 'ReducedRegistrationFeePending';
                break;
            case self :: REDUCED_REGISTRATION_FEE_REFUSED :
                return 'ReducedRegistrationFeeRefused';
                break;
        }
    }

    /**
     *
     * @param boolean $types_only
     * @return multitype:integer multitype:string
     */
    public static function get_reduced_registration_fee_types($types_only = false)
    {
        $types = array();
        
        $types[self :: REDUCED_REGISTRATION_FEE_NO] = self :: reduced_registration_fee_string(
            self :: REDUCED_REGISTRATION_FEE_NO);
        $types[self :: REDUCED_REGISTRATION_FEE_YES] = self :: reduced_registration_fee_string(
            self :: REDUCED_REGISTRATION_FEE_YES);
        $types[self :: REDUCED_REGISTRATION_FEE_ALMOST] = self :: reduced_registration_fee_string(
            self :: REDUCED_REGISTRATION_FEE_ALMOST);
        $types[self :: REDUCED_REGISTRATION_FEE_PENDING] = self :: reduced_registration_fee_string(
            self :: REDUCED_REGISTRATION_FEE_PENDING);
        $types[self :: REDUCED_REGISTRATION_FEE_REFUSED] = self :: reduced_registration_fee_string(
            self :: REDUCED_REGISTRATION_FEE_REFUSED);
        
        return ($types_only ? array_keys($types) : $types);
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
