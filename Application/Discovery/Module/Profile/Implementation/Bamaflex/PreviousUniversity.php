<?php
namespace Ehb\Application\Discovery\Module\Profile\Implementation\Bamaflex;

use Chamilo\Libraries\Storage\DataClass\DataClass;
use Chamilo\Libraries\Utilities\Utilities;

/**
 * application.discovery.module.profile.implementation.bamaflex.
 * 
 * @author GillardMagali
 */
class PreviousUniversity extends DataClass
{
    
    /**
     * PreviousUniversity properties
     */
    const PROPERTY_DATE = 'date';
    const PROPERTY_TYPE = 'type';
    const PROPERTY_SCHOOL_ID = 'school_id';
    const PROPERTY_SCHOOL_NAME = 'school_name';
    const PROPERTY_SCHOOL_CITY = 'school_city';
    const PROPERTY_TRAINING_ID = 'training_id';
    const PROPERTY_TRAINING_NAME = 'training_name';
    const PROPERTY_COUNTRY_ID = 'country_id';
    const PROPERTY_COUNTRY_NAME = 'country_name';
    const PROPERTY_INFO = 'info';
    
    // HOBU
    const TYPE_HIGHER_EDUCATION = 1;
    // UNIV
    const TYPE_UNIVERSITY = 2;
    // HOSP
    const TYPE_SPECIAL = 3;

    /**
     * Get the default properties
     * 
     * @param multitype:string $extended_property_names
     * @return multitype:string The property names.
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self::PROPERTY_DATE;
        $extended_property_names[] = self::PROPERTY_TYPE;
        $extended_property_names[] = self::PROPERTY_SCHOOL_ID;
        $extended_property_names[] = self::PROPERTY_SCHOOL_NAME;
        $extended_property_names[] = self::PROPERTY_SCHOOL_CITY;
        $extended_property_names[] = self::PROPERTY_TRAINING_ID;
        $extended_property_names[] = self::PROPERTY_TRAINING_NAME;
        $extended_property_names[] = self::PROPERTY_COUNTRY_ID;
        $extended_property_names[] = self::PROPERTY_COUNTRY_NAME;
        $extended_property_names[] = self::PROPERTY_INFO;
        
        return parent::get_default_property_names($extended_property_names);
    }

    /**
     * Get the data class data manager
     * 
     * @return DataManagerInterface
     */
    public function get_data_manager()
    {
        // return DataManager :: getInstance();
    }

    /**
     * Returns the date of this PreviousUniversity.
     * 
     * @return string The date.
     */
    public function get_date()
    {
        return $this->get_default_property(self::PROPERTY_DATE);
    }

    /**
     * Sets the date of this PreviousUniversity.
     * 
     * @param string $date
     */
    public function set_date($date)
    {
        $this->set_default_property(self::PROPERTY_DATE, $date);
    }

    /**
     * Returns the type of this PreviousUniversity.
     * 
     * @return int The type.
     */
    public function get_type()
    {
        return $this->get_default_property(self::PROPERTY_TYPE);
    }

    /**
     * Sets the type of this PreviousUniversity.
     * 
     * @param int $type
     */
    public function set_type($type)
    {
        $this->set_default_property(self::PROPERTY_TYPE, $type);
    }

    public function get_type_string()
    {
        return self::type_string($this->get_type());
    }

    public function type_string($type)
    {
        switch ($type)
        {
            case self::TYPE_HIGHER_EDUCATION :
                return 'HigherEducation';
                break;
            case self::TYPE_UNIVERSITY :
                return 'University';
                break;
            case self::TYPE_SPECIAL :
                return 'Special';
                break;
        }
    }

    /**
     * Returns the school_id of this PreviousUniversity.
     * 
     * @return int The school_id.
     */
    public function get_school_id()
    {
        return $this->get_default_property(self::PROPERTY_SCHOOL_ID);
    }

    /**
     * Sets the school_id of this PreviousUniversity.
     * 
     * @param int $school_id
     */
    public function set_school_id($school_id)
    {
        $this->set_default_property(self::PROPERTY_SCHOOL_ID, $school_id);
    }

    /**
     * Returns the school_name of this PreviousUniversity.
     * 
     * @return string The school_name.
     */
    public function get_school_name()
    {
        return $this->get_default_property(self::PROPERTY_SCHOOL_NAME);
    }

    /**
     * Sets the school_name of this PreviousUniversity.
     * 
     * @param string $school_name
     */
    public function set_school_name($school_name)
    {
        $this->set_default_property(self::PROPERTY_SCHOOL_NAME, $school_name);
    }

    /**
     * Returns the school_city of this PreviousUniversity.
     * 
     * @return string The school_city.
     */
    public function get_school_city()
    {
        return $this->get_default_property(self::PROPERTY_SCHOOL_CITY);
    }

    /**
     * Sets the school_city of this PreviousUniversity.
     * 
     * @param string $school_city
     */
    public function set_school_city($school_city)
    {
        $this->set_default_property(self::PROPERTY_SCHOOL_CITY, $school_city);
    }

    /**
     * Returns the training_id of this PreviousUniversity.
     * 
     * @return int The training_id.
     */
    public function get_training_id()
    {
        return $this->get_default_property(self::PROPERTY_TRAINING_ID);
    }

    /**
     * Sets the training_id of this PreviousUniversity.
     * 
     * @param int $training_id
     */
    public function set_training_id($training_id)
    {
        $this->set_default_property(self::PROPERTY_TRAINING_ID, $training_id);
    }

    /**
     * Returns the training_name of this PreviousUniversity.
     * 
     * @return string The training_name.
     */
    public function get_training_name()
    {
        return $this->get_default_property(self::PROPERTY_TRAINING_NAME);
    }

    /**
     * Sets the training_name of this PreviousUniversity.
     * 
     * @param string $training_name
     */
    public function set_training_name($training_name)
    {
        $this->set_default_property(self::PROPERTY_TRAINING_NAME, $training_name);
    }

    /**
     * Returns the country_id of this PreviousUniversity.
     * 
     * @return int The country_id.
     */
    public function get_country_id()
    {
        return $this->get_default_property(self::PROPERTY_COUNTRY_ID);
    }

    /**
     * Sets the country_id of this PreviousUniversity.
     * 
     * @param int $country_id
     */
    public function set_country_id($country_id)
    {
        $this->set_default_property(self::PROPERTY_COUNTRY_ID, $country_id);
    }

    /**
     * Returns the country_name of this PreviousUniversity.
     * 
     * @return string The country_name.
     */
    public function get_country_name()
    {
        return $this->get_default_property(self::PROPERTY_COUNTRY_NAME);
    }

    /**
     * Sets the country_name of this PreviousUniversity.
     * 
     * @param string $country_name
     */
    public function set_country_name($country_name)
    {
        $this->set_default_property(self::PROPERTY_COUNTRY_NAME, $country_name);
    }

    /**
     * Returns the info of this PreviousUniversity.
     * 
     * @return string The info.
     */
    public function get_info()
    {
        return $this->get_default_property(self::PROPERTY_INFO);
    }

    /**
     * Sets the info of this PreviousUniversity.
     * 
     * @param string $info
     */
    public function set_info($info)
    {
        $this->set_default_property(self::PROPERTY_INFO, $info);
    }

    /**
     *
     * @return string The table name of the data class
     */
    public static function get_table_name()
    {
        return Utilities::get_classname_from_namespace(self::class_name(), true);
    }
}
