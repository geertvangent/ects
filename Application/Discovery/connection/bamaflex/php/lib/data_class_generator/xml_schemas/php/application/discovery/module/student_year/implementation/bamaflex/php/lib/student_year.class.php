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

    const NO = 1;
    const YES = 2;
    const ALMOST = 3;
    const PENDING = 4;
    const REFUSED = 5;
    const NO = 1;
    const YES = 2;
    const ALMOST = 3;
    const PENDING = 4;
    const REFUSED = 5;

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
     * @return string The table name of the data class
     */
    static function get_table_name()
    {
        return Utilities :: get_classname_from_namespace(self :: CLASS_NAME, true);
    }
}
