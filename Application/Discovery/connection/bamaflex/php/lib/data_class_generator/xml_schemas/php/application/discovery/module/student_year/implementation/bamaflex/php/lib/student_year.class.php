<?php
namespace application\discovery\module\student_year\implementation\bamaflex;

use common\libraries\Utilities;
use common\libraries\DataClass;

/**
 * application.discovery.module.student_year.implementation.bamaflex
 * @author Hans De Bisschop
 */
class StudentYear extends DiscoveryItem
{
    const CLASS_NAME = __CLASS__;

    /**
     * @var string
     */
    const PROPERTY_SOURCE = 'source';
    /**
     * @var integer
     */
    const PROPERTY_PERSON_ID = 'person_id';
    /**
     * @var string
     */
    const PROPERTY_YEAR = 'year';
    /**
     * @var integer
     */
    const PROPERTY_ENROLLMENT_ID = 'enrollment_id';
    /**
     * @var integer
     */
    const PROPERTY_SCHOLARSHIP_ID = 'scholarship_id';
    /**
     * @var integer
     */
    const PROPERTY_REDUCED_REGISTRATION_FEE_ID = 'reduced_registration_fee_id';

    const SCHOLARSHIP_ID_NO = 1;
    const SCHOLARSHIP_ID_YES = 2;
    const SCHOLARSHIP_ID_ALMOST = 3;
    const SCHOLARSHIP_ID_PENDING = 4;
    const SCHOLARSHIP_ID_REFUSED = 5;

    const REDUCED_REGISTRATION_FEE_ID_NO = 1;
    const REDUCED_REGISTRATION_FEE_ID_YES = 2;
    const REDUCED_REGISTRATION_FEE_ID_ALMOST = 3;
    const REDUCED_REGISTRATION_FEE_ID_PENDING = 4;
    const REDUCED_REGISTRATION_FEE_ID_REFUSED = 5;


    /**
     * Get the default properties
     * @param multitype:string $extended_property_names
     * @return multitype:string The property names.
     */
    static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_SOURCE;
        $extended_property_names[] = self :: PROPERTY_PERSON_ID;
        $extended_property_names[] = self :: PROPERTY_YEAR;
        $extended_property_names[] = self :: PROPERTY_ENROLLMENT_ID;
        $extended_property_names[] = self :: PROPERTY_SCHOLARSHIP_ID;
        $extended_property_names[] = self :: PROPERTY_REDUCED_REGISTRATION_FEE_ID;

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
     * Returns the source of this StudentYear.
     * @return string The source.
     */
    function get_source()
    {
        return $this->get_default_property(self :: PROPERTY_SOURCE);
    }

    /**
     * Sets the source of this StudentYear.
     * @param string $source
     */
    function set_source($source)
    {
        $this->set_default_property(self :: PROPERTY_SOURCE, $source);
    }

    /**
     * Returns the person_id of this StudentYear.
     * @return integer The person_id.
     */
    function get_person_id()
    {
        return $this->get_default_property(self :: PROPERTY_PERSON_ID);
    }

    /**
     * Sets the person_id of this StudentYear.
     * @param integer $person_id
     */
    function set_person_id($person_id)
    {
        $this->set_default_property(self :: PROPERTY_PERSON_ID, $person_id);
    }

    /**
     * Returns the year of this StudentYear.
     * @return string The year.
     */
    function get_year()
    {
        return $this->get_default_property(self :: PROPERTY_YEAR);
    }

    /**
     * Sets the year of this StudentYear.
     * @param string $year
     */
    function set_year($year)
    {
        $this->set_default_property(self :: PROPERTY_YEAR, $year);
    }

    /**
     * Returns the enrollment_id of this StudentYear.
     * @return integer The enrollment_id.
     */
    function get_enrollment_id()
    {
        return $this->get_default_property(self :: PROPERTY_ENROLLMENT_ID);
    }

    /**
     * Sets the enrollment_id of this StudentYear.
     * @param integer $enrollment_id
     */
    function set_enrollment_id($enrollment_id)
    {
        $this->set_default_property(self :: PROPERTY_ENROLLMENT_ID, $enrollment_id);
    }

    /**
     * Returns the scholarship_id of this StudentYear.
     * @return integer The scholarship_id.
     */
    function get_scholarship_id()
    {
        return $this->get_default_property(self :: PROPERTY_SCHOLARSHIP_ID);
    }

    /**
     * Sets the scholarship_id of this StudentYear.
     * @param integer $scholarship_id
     */
    function set_scholarship_id($scholarship_id)
    {
        $this->set_default_property(self :: PROPERTY_SCHOLARSHIP_ID, $scholarship_id);
    }

    /**
     * Returns the reduced_registration_fee_id of this StudentYear.
     * @return integer The reduced_registration_fee_id.
     */
    function get_reduced_registration_fee_id()
    {
        return $this->get_default_property(self :: PROPERTY_REDUCED_REGISTRATION_FEE_ID);
    }

    /**
     * Sets the reduced_registration_fee_id of this StudentYear.
     * @param integer $reduced_registration_fee_id
     */
    function set_reduced_registration_fee_id($reduced_registration_fee_id)
    {
        $this->set_default_property(self :: PROPERTY_REDUCED_REGISTRATION_FEE_ID, $reduced_registration_fee_id);
    }

	/**
     * @return string
     */
    function get_scholarship_string()
    {
        return self :: scholarship_string($this->get_scholarship());
    }

    /**
     * @return string
     */
    static function scholarship_string($scholarship)
    {
        switch ($scholarship)
        {
            case self :: SCHOLARSHIP_NO :
                return 'ScholarshipNo';
                break;
            case self :: SCHOLARSHIP_YES :
                return 'ScholarshipYes';
                break;
            case self :: SCHOLARSHIP_ALMOST :
                return 'ScholarshipAlmost';
                break;
            case self :: SCHOLARSHIP_PENDING :
                return 'ScholarshipPending';
                break;
            case self :: SCHOLARSHIP_REFUSED :
                return 'ScholarshipRefused';
                break;
        }
    }

    /**
     * @param boolean $types_only
     * @return multitype:integer|multitype:string
     */
    static function get_scholarship_types($types_only = false)
    {
    	$types = array();

        $types[self :: SCHOLARSHIP_NO] = self :: scholarship_string(self :: SCHOLARSHIP_NO);
        $types[self :: SCHOLARSHIP_YES] = self :: scholarship_string(self :: SCHOLARSHIP_YES);
        $types[self :: SCHOLARSHIP_ALMOST] = self :: scholarship_string(self :: SCHOLARSHIP_ALMOST);
        $types[self :: SCHOLARSHIP_PENDING] = self :: scholarship_string(self :: SCHOLARSHIP_PENDING);
        $types[self :: SCHOLARSHIP_REFUSED] = self :: scholarship_string(self :: SCHOLARSHIP_REFUSED);

    	return ($types_only ? array_keys($types) : $types);
    }
	/**
     * @return string
     */
    function get_reduced_registration_fee_string()
    {
        return self :: reduced_registration_fee_string($this->get_reduced_registration_fee());
    }

    /**
     * @return string
     */
    static function reduced_registration_fee_string($reduced_registration_fee)
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
     * @param boolean $types_only
     * @return multitype:integer|multitype:string
     */
    static function get_reduced_registration_fee_types($types_only = false)
    {
    	$types = array();

        $types[self :: REDUCED_REGISTRATION_FEE_NO] = self :: reduced_registration_fee_string(self :: REDUCED_REGISTRATION_FEE_NO);
        $types[self :: REDUCED_REGISTRATION_FEE_YES] = self :: reduced_registration_fee_string(self :: REDUCED_REGISTRATION_FEE_YES);
        $types[self :: REDUCED_REGISTRATION_FEE_ALMOST] = self :: reduced_registration_fee_string(self :: REDUCED_REGISTRATION_FEE_ALMOST);
        $types[self :: REDUCED_REGISTRATION_FEE_PENDING] = self :: reduced_registration_fee_string(self :: REDUCED_REGISTRATION_FEE_PENDING);
        $types[self :: REDUCED_REGISTRATION_FEE_REFUSED] = self :: reduced_registration_fee_string(self :: REDUCED_REGISTRATION_FEE_REFUSED);

    	return ($types_only ? array_keys($types) : $types);
    }

    /**
     * @return string The table name of the data class
     */
    static function get_table_name()
    {
        return Utilities :: get_classname_from_namespace(self :: CLASS_NAME, true);
    }
}
