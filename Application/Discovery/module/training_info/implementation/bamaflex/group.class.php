<?php
namespace application\discovery\module\training_info\implementation\bamaflex;

use application\discovery\DiscoveryItem;
use libraries\utilities\Utilities;

/**
 * application.discovery.module.training.implementation.bamaflex
 * 
 * @author Hans De Bisschop
 */
class Group extends DiscoveryItem
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
    const PROPERTY_TRAINING_ID = 'training_id';
    /**
     *
     * @var integer
     */
    const PROPERTY_GROUP_ID = 'group_id';
    /**
     *
     * @var string
     */
    const PROPERTY_GROUP = 'group';

    /**
     * Get the default properties
     * 
     * @param multitype:string $extended_property_names
     * @return multitype:string The property names.
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_SOURCE;
        $extended_property_names[] = self :: PROPERTY_TRAINING_ID;
        $extended_property_names[] = self :: PROPERTY_GROUP_ID;
        $extended_property_names[] = self :: PROPERTY_GROUP;
        
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
     * Returns the source of this Group.
     * 
     * @return string The source.
     */
    public function get_source()
    {
        return $this->get_default_property(self :: PROPERTY_SOURCE);
    }

    /**
     * Sets the source of this Group.
     * 
     * @param string $source
     */
    public function set_source($source)
    {
        $this->set_default_property(self :: PROPERTY_SOURCE, $source);
    }

    /**
     * Returns the training_id of this Group.
     * 
     * @return integer The training_id.
     */
    public function get_training_id()
    {
        return $this->get_default_property(self :: PROPERTY_TRAINING_ID);
    }

    /**
     * Sets the training_id of this Group.
     * 
     * @param integer $training_id
     */
    public function set_training_id($training_id)
    {
        $this->set_default_property(self :: PROPERTY_TRAINING_ID, $training_id);
    }

    /**
     * Returns the group_id of this Group.
     * 
     * @return integer The group_id.
     */
    public function get_group_id()
    {
        return $this->get_default_property(self :: PROPERTY_GROUP_ID);
    }

    /**
     * Sets the group_id of this Group.
     * 
     * @param integer $group_id
     */
    public function set_group_id($group_id)
    {
        $this->set_default_property(self :: PROPERTY_GROUP_ID, $group_id);
    }

    /**
     * Returns the group of this Group.
     * 
     * @return string The group.
     */
    public function get_group()
    {
        return $this->get_default_property(self :: PROPERTY_GROUP);
    }

    /**
     * Sets the group of this Group.
     * 
     * @param string $group
     */
    public function set_group($group)
    {
        $this->set_default_property(self :: PROPERTY_GROUP, $group);
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
