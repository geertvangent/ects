<?php
namespace Chamilo\Application\Discovery\DataSource\Bamaflex;

use Chamilo\Libraries\Storage\DataClass\DataClass;
use Chamilo\Libraries\Utilities\Utilities;

/**
 * application.discovery.connection.bamaflex.
 * 
 * @author GillardMagali
 */
class History extends DataClass
{
    const CLASS_NAME = __CLASS__;
    
    /**
     * History properties
     */
    const PROPERTY_HISTORY_ID = 'history_id';
    const PROPERTY_HISTORY_SOURCE = 'history_source';
    const PROPERTY_PREVIOUS_ID = 'previous_id';
    const PROPERTY_PREVIOUS_SOURCE = 'previous_source';
    const PROPERTY_TYPE = 'type';

    /**
     * Get the default properties
     * 
     * @param multitype:string $extended_property_names
     * @return multitype:string The property names.
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_HISTORY_ID;
        $extended_property_names[] = self :: PROPERTY_HISTORY_SOURCE;
        $extended_property_names[] = self :: PROPERTY_PREVIOUS_ID;
        $extended_property_names[] = self :: PROPERTY_PREVIOUS_SOURCE;
        $extended_property_names[] = self :: PROPERTY_TYPE;
        
        return parent :: get_default_property_names($extended_property_names);
    }

    /**
     * Get the data class data manager
     * 
     * @return DataManagerInterface
     */
    public function get_data_manager()
    {
        return DataManager :: get_instance();
    }

    /**
     * Returns the history_id of this History.
     * 
     * @return int The history_id.
     */
    public function get_history_id()
    {
        return $this->get_default_property(self :: PROPERTY_HISTORY_ID);
    }

    /**
     * Sets the history_id of this History.
     * 
     * @param int $history_id
     */
    public function set_history_id($history_id)
    {
        $this->set_default_property(self :: PROPERTY_HISTORY_ID, $history_id);
    }

    /**
     * Returns the history_source of this History.
     * 
     * @return int The history_source.
     */
    public function get_history_source()
    {
        return $this->get_default_property(self :: PROPERTY_HISTORY_SOURCE);
    }

    /**
     * Sets the history_source of this History.
     * 
     * @param int $history_source
     */
    public function set_history_source($history_source)
    {
        $this->set_default_property(self :: PROPERTY_HISTORY_SOURCE, $history_source);
    }

    /**
     * Returns the previous_id of this History.
     * 
     * @return int The previous_id.
     */
    public function get_previous_id()
    {
        return $this->get_default_property(self :: PROPERTY_PREVIOUS_ID);
    }

    /**
     * Sets the previous_id of this History.
     * 
     * @param int $previous_id
     */
    public function set_previous_id($previous_id)
    {
        $this->set_default_property(self :: PROPERTY_PREVIOUS_ID, $previous_id);
    }

    /**
     * Returns the previous_source of this History.
     * 
     * @return int The previous_source.
     */
    public function get_previous_source()
    {
        return $this->get_default_property(self :: PROPERTY_PREVIOUS_SOURCE);
    }

    /**
     * Sets the previous_source of this History.
     * 
     * @param int $previous_source
     */
    public function set_previous_source($previous_source)
    {
        $this->set_default_property(self :: PROPERTY_PREVIOUS_SOURCE, $previous_source);
    }

    /**
     * Returns the type of this History.
     * 
     * @return string The type.
     */
    public function get_type()
    {
        return $this->get_default_property(self :: PROPERTY_TYPE);
    }

    /**
     * Sets the type of this History.
     * 
     * @param string $type
     */
    public function set_type($type)
    {
        $this->set_default_property(self :: PROPERTY_TYPE, $type);
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
