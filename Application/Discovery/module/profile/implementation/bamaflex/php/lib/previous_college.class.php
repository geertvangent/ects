<?php
namespace application\discovery\module\profile\implementation\bamaflex;

use common\libraries\Utilities;
use common\libraries\DataClass;

/**
 * application.discovery.module.profile.implementation.bamaflex.
 * 
 * @author GillardMagali
 */
class PreviousCollege extends DataClass
{
    const CLASS_NAME = __CLASS__;
    
    /**
     * PreviousCollege properties
     */
    const PROPERTY_DATE = 'date';
    const PROPERTY_DEGREE_ID = 'degree_id';
    const PROPERTY_DEGREE_TYPE = 'degree_type';
    const PROPERTY_DEGREE_NAME = 'degree_name';
    const PROPERTY_SCHOOL_ID = 'school_id';
    const PROPERTY_SCHOOL_NAME = 'school_name';
    const PROPERTY_SCHOOL_CITY = 'school_city';
    const PROPERTY_TRAINING_ID = 'training_id';
    const PROPERTY_TRAINING_NAME = 'training_name';
    const PROPERTY_COUNTRY_ID = 'country_id';
    const PROPERTY_COUNTRY_NAME = 'country_name';
    const PROPERTY_INFO = 'info';

    /**
     * Get the default properties
     * 
     * @param multitype:string $extended_property_names
     * @return multitype:string The property names.
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_DATE;
        $extended_property_names[] = self :: PROPERTY_DEGREE_ID;
        $extended_property_names[] = self :: PROPERTY_DEGREE_TYPE;
        $extended_property_names[] = self :: PROPERTY_DEGREE_NAME;
        $extended_property_names[] = self :: PROPERTY_SCHOOL_ID;
        $extended_property_names[] = self :: PROPERTY_SCHOOL_NAME;
        $extended_property_names[] = self :: PROPERTY_SCHOOL_CITY;
        $extended_property_names[] = self :: PROPERTY_TRAINING_ID;
        $extended_property_names[] = self :: PROPERTY_TRAINING_NAME;
        $extended_property_names[] = self :: PROPERTY_COUNTRY_ID;
        $extended_property_names[] = self :: PROPERTY_COUNTRY_NAME;
        $extended_property_names[] = self :: PROPERTY_INFO;
        
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
     * Returns the date of this PreviousCollege.
     * 
     * @return string The date.
     */
    public function get_date()
    {
        return $this->get_default_property(self :: PROPERTY_DATE);
    }

    /**
     * Sets the date of this PreviousCollege.
     * 
     * @param string $date
     */
    public function set_date($date)
    {
        $this->set_default_property(self :: PROPERTY_DATE, $date);
    }

    /**
     * Returns the degree_id of this PreviousCollege.
     * 
     * @return int The degree_id.
     */
    public function get_degree_id()
    {
        return $this->get_default_property(self :: PROPERTY_DEGREE_ID);
    }

    /**
     * Sets the degree_id of this PreviousCollege.
     * 
     * @param int $degree_id
     */
    public function set_degree_id($degree_id)
    {
        $this->set_default_property(self :: PROPERTY_DEGREE_ID, $degree_id);
    }

    /**
     * Returns the degree_type of this PreviousCollege.
     * 
     * @return int The degree_type.
     */
    public function get_degree_type()
    {
        return $this->get_default_property(self :: PROPERTY_DEGREE_TYPE);
    }

    /**
     * Sets the degree_type of this PreviousCollege.
     * 
     * @param int $degree_type
     */
    public function set_degree_type($degree_type)
    {
        $this->set_default_property(self :: PROPERTY_DEGREE_TYPE, $degree_type);
    }

    /**
     * Returns the degree_name of this PreviousCollege.
     * 
     * @return string The degree_name.
     */
    public function get_degree_name()
    {
        return $this->get_default_property(self :: PROPERTY_DEGREE_NAME);
    }

    /**
     * Sets the degree_name of this PreviousCollege.
     * 
     * @param string $degree_name
     */
    public function set_degree_name($degree_name)
    {
        $this->set_default_property(self :: PROPERTY_DEGREE_NAME, $degree_name);
    }

    /**
     * Returns the school_id of this PreviousCollege.
     * 
     * @return int The school_id.
     */
    public function get_school_id()
    {
        return $this->get_default_property(self :: PROPERTY_SCHOOL_ID);
    }

    /**
     * Sets the school_id of this PreviousCollege.
     * 
     * @param int $school_id
     */
    public function set_school_id($school_id)
    {
        $this->set_default_property(self :: PROPERTY_SCHOOL_ID, $school_id);
    }

    /**
     * Returns the school_name of this PreviousCollege.
     * 
     * @return string The school_name.
     */
    public function get_school_name()
    {
        return $this->get_default_property(self :: PROPERTY_SCHOOL_NAME);
    }

    /**
     * Sets the school_name of this PreviousCollege.
     * 
     * @param string $school_name
     */
    public function set_school_name($school_name)
    {
        $this->set_default_property(self :: PROPERTY_SCHOOL_NAME, $school_name);
    }

    /**
     * Returns the school_city of this PreviousCollege.
     * 
     * @return string The school_city.
     */
    public function get_school_city()
    {
        return $this->get_default_property(self :: PROPERTY_SCHOOL_CITY);
    }

    /**
     * Sets the school_city of this PreviousCollege.
     * 
     * @param string $school_city
     */
    public function set_school_city($school_city)
    {
        $this->set_default_property(self :: PROPERTY_SCHOOL_CITY, $school_city);
    }

    /**
     * Returns the training_id of this PreviousCollege.
     * 
     * @return int The training_id.
     */
    public function get_training_id()
    {
        return $this->get_default_property(self :: PROPERTY_TRAINING_ID);
    }

    /**
     * Sets the training_id of this PreviousCollege.
     * 
     * @param int $training_id
     */
    public function set_training_id($training_id)
    {
        $this->set_default_property(self :: PROPERTY_TRAINING_ID, $training_id);
    }

    /**
     * Returns the training_name of this PreviousCollege.
     * 
     * @return string The training_name.
     */
    public function get_training_name()
    {
        return $this->get_default_property(self :: PROPERTY_TRAINING_NAME);
    }

    /**
     * Sets the training_name of this PreviousCollege.
     * 
     * @param string $training_name
     */
    public function set_training_name($training_name)
    {
        $this->set_default_property(self :: PROPERTY_TRAINING_NAME, $training_name);
    }

    /**
     * Returns the country_id of this PreviousCollege.
     * 
     * @return int The country_id.
     */
    public function get_country_id()
    {
        return $this->get_default_property(self :: PROPERTY_COUNTRY_ID);
    }

    /**
     * Sets the country_id of this PreviousCollege.
     * 
     * @param int $country_id
     */
    public function set_country_id($country_id)
    {
        $this->set_default_property(self :: PROPERTY_COUNTRY_ID, $country_id);
    }

    /**
     * Returns the country_name of this PreviousCollege.
     * 
     * @return string The country_name.
     */
    public function get_country_name()
    {
        return $this->get_default_property(self :: PROPERTY_COUNTRY_NAME);
    }

    /**
     * Sets the country_name of this PreviousCollege.
     * 
     * @param string $country_name
     */
    public function set_country_name($country_name)
    {
        $this->set_default_property(self :: PROPERTY_COUNTRY_NAME, $country_name);
    }

    /**
     * Returns the info of this PreviousCollege.
     * 
     * @return string The info.
     */
    public function get_info()
    {
        return $this->get_default_property(self :: PROPERTY_INFO);
    }

    /**
     * Sets the info of this PreviousCollege.
     * 
     * @param string $info
     */
    public function set_info($info)
    {
        $this->set_default_property(self :: PROPERTY_INFO, $info);
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
