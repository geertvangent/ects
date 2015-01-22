<?php
namespace Chamilo\Application\Discovery\Module\Career;

use Chamilo\Libraries\Storage\DataClass\DataClass;
use Chamilo\Libraries\Utilities\Utilities;

/**
 * application.discovery.module.career.discovery
 * 
 * @author Hans De Bisschop
 */
class Mark extends DataClass
{
    const CLASS_NAME = __CLASS__;
    
    /**
     * Mark properties
     */
    const PROPERTY_MOMENT = 'moment';
    const PROPERTY_RESULT = 'result';
    const PROPERTY_STATUS = 'status';

    /**
     * Get the default properties
     * 
     * @param multitype:string $extended_property_names
     * @return multitype:string The property names.
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_MOMENT;
        $extended_property_names[] = self :: PROPERTY_RESULT;
        $extended_property_names[] = self :: PROPERTY_STATUS;
        
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
     * Returns the moment of this Mark.
     * 
     * @return string The moment.
     */
    public function get_moment()
    {
        return $this->get_default_property(self :: PROPERTY_MOMENT);
    }

    /**
     * Sets the moment of this Mark.
     * 
     * @param string $moment
     */
    public function set_moment($moment)
    {
        $this->set_default_property(self :: PROPERTY_MOMENT, $moment);
    }

    /**
     * Returns the result of this Mark.
     * 
     * @return string The result.
     */
    public function get_result()
    {
        return $this->get_default_property(self :: PROPERTY_RESULT);
    }

    /**
     * Sets the result of this Mark.
     * 
     * @param string $result
     */
    public function set_result($result)
    {
        $this->set_default_property(self :: PROPERTY_RESULT, $result);
    }

    public function get_visual_result()
    {
        return (is_numeric($this->get_result()) ? (float) $this->get_result() : $this->get_result());
    }

    /**
     * Returns the status of this Mark.
     * 
     * @return string The status.
     */
    public function get_status()
    {
        return $this->get_default_property(self :: PROPERTY_STATUS);
    }

    /**
     * Sets the status of this Mark.
     * 
     * @param string $status
     */
    public function set_status($status)
    {
        $this->set_default_property(self :: PROPERTY_STATUS, $status);
    }

    /**
     *
     * @return string
     */
    public function get_status_string()
    {
        return self :: status_string($this->get_status());
    }

    /**
     *
     * @return string
     */
    public static function status_string($status)
    {
        switch ($status)
        {
            default :
                return '';
                break;
        }
    }

    /**
     *
     * @return string The table name of the data class
     */
    public static function get_table_name()
    {
        return Utilities :: get_classname_from_namespace(self :: CLASS_NAME, true);
    }

    public static function factory($moment_id = 0, $result = null, $status = null)
    {
        $mark = new self();
        $mark->set_moment($moment_id);
        $mark->set_result($result);
        $mark->set_status($status);
        return $mark;
    }
}
