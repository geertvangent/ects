<?php
namespace Ehb\Application\Discovery\Module\StudentYear;

use Ehb\Application\Discovery\DiscoveryItem;
use Chamilo\Libraries\Utilities\Utilities;

/**
 * application.discovery.module.student_year.implementation.bamaflex
 *
 * @author Hans De Bisschop
 */
class StudentYear extends DiscoveryItem
{

    /**
     *
     * @var integer
     */
    const PROPERTY_PERSON_ID = 'person_id';
    /**
     *
     * @var string
     */
    const PROPERTY_YEAR = 'year';
    /**
     *
     * @var integer
     */
    const PROPERTY_ENROLLMENT_ID = 'enrollment_id';
    /**
     *
     * @var integer
     */
    const PROPERTY_SCHOLARSHIP_ID = 'scholarship_id';
    const SCHOLARSHIP_NO = 1;
    const SCHOLARSHIP_YES = 2;
    const SCHOLARSHIP_ALMOST = 3;
    const SCHOLARSHIP_PENDING = 4;
    const SCHOLARSHIP_REFUSED = 5;

    /**
     * Get the default properties
     *
     * @param multitype:string $extended_property_names
     * @return multitype:string The property names.
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_PERSON_ID;
        $extended_property_names[] = self :: PROPERTY_YEAR;
        $extended_property_names[] = self :: PROPERTY_ENROLLMENT_ID;
        $extended_property_names[] = self :: PROPERTY_SCHOLARSHIP_ID;

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
     * Returns the person_id of this StudentYear.
     *
     * @return integer The person_id.
     */
    public function get_person_id()
    {
        return $this->get_default_property(self :: PROPERTY_PERSON_ID);
    }

    /**
     * Sets the person_id of this StudentYear.
     *
     * @param integer $person_id
     */
    public function set_person_id($person_id)
    {
        $this->set_default_property(self :: PROPERTY_PERSON_ID, $person_id);
    }

    /**
     * Returns the year of this StudentYear.
     *
     * @return string The year.
     */
    public function get_year()
    {
        return $this->get_default_property(self :: PROPERTY_YEAR);
    }

    /**
     * Sets the year of this StudentYear.
     *
     * @param string $year
     */
    public function set_year($year)
    {
        $this->set_default_property(self :: PROPERTY_YEAR, $year);
    }

    /**
     * Returns the enrollment_id of this StudentYear.
     *
     * @return integer The enrollment_id.
     */
    public function get_enrollment_id()
    {
        return $this->get_default_property(self :: PROPERTY_ENROLLMENT_ID);
    }

    /**
     * Sets the enrollment_id of this StudentYear.
     *
     * @param integer $enrollment_id
     */
    public function set_enrollment_id($enrollment_id)
    {
        $this->set_default_property(self :: PROPERTY_ENROLLMENT_ID, $enrollment_id);
    }

    /**
     * Returns the scholarship_id of this StudentYear.
     *
     * @return integer The scholarship_id.
     */
    public function get_scholarship_id()
    {
        return $this->get_default_property(self :: PROPERTY_SCHOLARSHIP_ID);
    }

    /**
     * Sets the scholarship_id of this StudentYear.
     *
     * @param integer $scholarship_id
     */
    public function set_scholarship_id($scholarship_id)
    {
        $this->set_default_property(self :: PROPERTY_SCHOLARSHIP_ID, $scholarship_id);
    }

    /**
     *
     * @return string
     */
    public function get_scholarship_string()
    {
        return self :: scholarship_string($this->get_scholarship_id());
    }

    /**
     *
     * @return string
     */
    public static function scholarship_string($scholarship)
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
     *
     * @param boolean $types_only
     * @return multitype:integer multitype:string
     */
    public static function get_scholarship_types($types_only = false)
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
     *
     * @return string The table name of the data class
     */
    public static function get_table_name()
    {
        return Utilities :: get_classname_from_namespace(self :: class_name(), true);
    }
}
