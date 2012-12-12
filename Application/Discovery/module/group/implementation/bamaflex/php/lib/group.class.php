<?php
namespace application\discovery\module\group\implementation\bamaflex;

use application\discovery\DiscoveryDataManager;
use application\discovery\DiscoveryItem;
use common\libraries\Utilities;
use common\libraries\DataClass;

/**
 * application.discovery.module.group.implementation.bamaflex
 * 
 * @author Magali Gillard
 */
class Group extends DiscoveryItem
{
    const CLASS_NAME = __CLASS__;
    
    /**
     *
     * @var integer
     */
    const PROPERTY_TRAINING_ID = 'training_id';
    /**
     *
     * @var string
     */
    const PROPERTY_YEAR = 'year';
    /**
     *
     * @var string
     */
    const PROPERTY_CODE = 'code';
    /**
     *
     * @var string
     */
    const PROPERTY_DESCRIPTION = 'description';
    /**
     *
     * @var integer
     */
    const PROPERTY_TYPE = 'type';
    /**
     *
     * @var integer
     */
    const PROPERTY_TYPE_ID = 'type_id';
    const PROPERTY_SOURCE = 'source';
    const TYPE_TRAINING = 1;
    const TYPE_CLASS = 2;
    const TYPE_CUSTOM = 3;
    const TYPE_CLASS_COURSE = 4;

    /**
     * Get the default properties
     * 
     * @param multitype:string $extended_property_names
     * @return multitype:string The property names.
     */
    static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_TRAINING_ID;
        $extended_property_names[] = self :: PROPERTY_YEAR;
        $extended_property_names[] = self :: PROPERTY_CODE;
        $extended_property_names[] = self :: PROPERTY_DESCRIPTION;
        $extended_property_names[] = self :: PROPERTY_TYPE;
        $extended_property_names[] = self :: PROPERTY_TYPE_ID;
        $extended_property_names[] = self :: PROPERTY_SOURCE;
        
        return parent :: get_default_property_names($extended_property_names);
    }

    /**
     * Get the data class data manager
     * 
     * @return DiscoveryDataManagerInterface
     */
    function get_data_manager()
    {
        return DiscoveryDataManager :: get_instance();
    }

    /**
     * Returns the training_id of this Group.
     * 
     * @return integer The training_id.
     */
    function get_training_id()
    {
        return $this->get_default_property(self :: PROPERTY_TRAINING_ID);
    }

    /**
     * Sets the training_id of this Group.
     * 
     * @param integer $training_id
     */
    function set_training_id($training_id)
    {
        $this->set_default_property(self :: PROPERTY_TRAINING_ID, $training_id);
    }

    /**
     * Returns the year of this Group.
     * 
     * @return string The year.
     */
    function get_year()
    {
        return $this->get_default_property(self :: PROPERTY_YEAR);
    }

    /**
     * Sets the year of this Group.
     * 
     * @param string $year
     */
    function set_year($year)
    {
        $this->set_default_property(self :: PROPERTY_YEAR, $year);
    }

    /**
     * Returns the code of this Group.
     * 
     * @return string The code.
     */
    function get_code()
    {
        return $this->get_default_property(self :: PROPERTY_CODE);
    }

    /**
     * Sets the code of this Group.
     * 
     * @param string $code
     */
    function set_code($code)
    {
        $this->set_default_property(self :: PROPERTY_CODE, $code);
    }

    /**
     * Returns the description of this Group.
     * 
     * @return string The description.
     */
    function get_description()
    {
        return $this->get_default_property(self :: PROPERTY_DESCRIPTION);
    }

    /**
     * Sets the description of this Group.
     * 
     * @param string $description
     */
    function set_description($description)
    {
        $this->set_default_property(self :: PROPERTY_DESCRIPTION, $description);
    }

    /**
     * Returns the type of this Group.
     * 
     * @return integer The type.
     */
    function get_type()
    {
        return $this->get_default_property(self :: PROPERTY_TYPE);
    }

    /**
     * Sets the type of this Group.
     * 
     * @param integer $type
     */
    function set_type($type)
    {
        $this->set_default_property(self :: PROPERTY_TYPE, $type);
    }

    /**
     * Returns the type_id of this Group.
     * 
     * @return integer The type_id.
     */
    function get_type_id()
    {
        return $this->get_default_property(self :: PROPERTY_TYPE_ID);
    }

    /**
     * Sets the type_id of this Group.
     * 
     * @param integer $type_id
     */
    function set_type_id($type_id)
    {
        $this->set_default_property(self :: PROPERTY_TYPE_ID, $type_id);
    }

    function get_source()
    {
        return $this->get_default_property(self :: PROPERTY_SOURCE);
    }

    function set_source($source)
    {
        $this->set_default_property(self :: PROPERTY_SOURCE, $source);
    }

    /**
     *
     * @return string
     */
    function get_type_string()
    {
        return self :: type_string($this->get_type());
    }

    /**
     *
     * @return string
     */
    static function type_string($type)
    {
        switch ($type)
        {
            case self :: TYPE_TRAINING :
                return 'TypeTraining';
                break;
            case self :: TYPE_CLASS :
                return 'TypeClass';
                break;
            case self :: TYPE_CUSTOM :
                return 'TypeCustom';
                break;
            case self :: TYPE_CLASS_COURSE :
                return 'TypeClassCourse';
                break;
        }
    }

    /**
     *
     * @param boolean $types_only
     * @return multitype:integer multitype:string
     */
    static function get_type_types($types_only = false)
    {
        $types = array();
        
        $types[self :: TYPE_TRAINING] = self :: type_string(self :: TYPE_TRAINING);
        $types[self :: TYPE_CLASS] = self :: type_string(self :: TYPE_CLASS);
        $types[self :: TYPE_CUSTOM] = self :: type_string(self :: TYPE_CUSTOM);
        $types[self :: TYPE_CLASS_COURSE] = self :: type_string(self :: TYPE_CLASS_COURSE);
        
        return ($types_only ? array_keys($types) : $types);
    }

    /**
     *
     * @return string The table name of the data class
     */
    static function get_table_name()
    {
        return Utilities :: get_classname_from_namespace(self :: CLASS_NAME, true);
    }
}
