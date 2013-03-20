<?php
namespace application\atlantis\application\right;

use application\atlantis\application\Application;
use common\libraries\Utilities;
use common\libraries\DataClass;

/**
 * application.atlantis.application.right.
 * 
 * @author GillardMagali
 */
class Right extends DataClass
{
    const CLASS_NAME = __CLASS__;
    
    /**
     * Right properties
     */
    const PROPERTY_NAME = 'name';
    const PROPERTY_DESCRIPTION = 'description';
    const PROPERTY_APPLICATION_ID = 'application_id';
    const PROPERTY_CODE = 'code';

    private $application;

    /**
     * Get the default properties
     * 
     * @param $extended_property_names multitype:string
     * @return multitype:string The property names.
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_NAME;
        $extended_property_names[] = self :: PROPERTY_DESCRIPTION;
        $extended_property_names[] = self :: PROPERTY_APPLICATION_ID;
        $extended_property_names[] = self :: PROPERTY_CODE;
        
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
     * Returns the name of this Right.
     * 
     * @return text The name.
     */
    public function get_name()
    {
        return $this->get_default_property(self :: PROPERTY_NAME);
    }

    /**
     * Sets the name of this Right.
     * 
     * @param $name text
     */
    public function set_name($name)
    {
        $this->set_default_property(self :: PROPERTY_NAME, $name);
    }

    /**
     * Returns the description of this Right.
     * 
     * @return text The description.
     */
    public function get_description()
    {
        return $this->get_default_property(self :: PROPERTY_DESCRIPTION);
    }

    /**
     * Sets the description of this Right.
     * 
     * @param $description text
     */
    public function set_description($description)
    {
        $this->set_default_property(self :: PROPERTY_DESCRIPTION, $description);
    }

    /**
     * Returns the application_id of this Right.
     * 
     * @return int The application_id.
     */
    public function get_application_id()
    {
        return $this->get_default_property(self :: PROPERTY_APPLICATION_ID);
    }

    public function get_application()
    {
        if (! isset($this->application))
        {
            $this->application = \application\atlantis\application\DataManager :: retrieve(
                Application :: class_name(), 
                (int) $this->get_application_id());
        }
        return $this->application;
    }

    /**
     * Sets the application_id of this Right.
     * 
     * @param $application_id int
     */
    public function set_application_id($application_id)
    {
        $this->set_default_property(self :: PROPERTY_APPLICATION_ID, $application_id);
    }

    public function get_code()
    {
        return $this->get_default_property(self :: PROPERTY_CODE);
    }

    public function set_code($code)
    {
        $this->set_default_property(self :: PROPERTY_CODE, $code);
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
