<?php
namespace application\atlantis\role;

use common\libraries\Utilities;
use common\libraries\DataClass;

/**
 * application.atlantis.role.
 * @author GillardMagali
 */
class Role extends DataClass
{
    const CLASS_NAME = __CLASS__;

    /**
     * Role properties
     */
    const PROPERTY_NAME = 'name';
    const PROPERTY_DESCRIPTION = 'description';

    /**
     * Get the default properties
     * @param multitype:string $extended_property_names
     * @return multitype:string The property names.
     */
    static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_NAME;
        $extended_property_names[] = self :: PROPERTY_DESCRIPTION;

        return parent :: get_default_property_names($extended_property_names);
    }

    /**
     * Get the data class data manager
     * @return DataManagerInterface
     */
    function get_data_manager()
    {
        return DataManager :: get_instance();
    }

    /**
     * Returns the name of this Role.
     * @return text The name.
     */
    function get_name()
    {
        return $this->get_default_property(self :: PROPERTY_NAME);
    }

    /**
     * Sets the name of this Role.
     * @param text $name
     */
    function set_name($name)
    {
        $this->set_default_property(self :: PROPERTY_NAME, $name);
    }
    /**
     * Returns the description of this Role.
     * @return text The description.
     */
    function get_description()
    {
        return $this->get_default_property(self :: PROPERTY_DESCRIPTION);
    }

    /**
     * Sets the description of this Role.
     * @param text $description
     */
    function set_description($description)
    {
        $this->set_default_property(self :: PROPERTY_DESCRIPTION, $description);
    }

    /**
     * @return string The table name of the data class
     */
    static function get_table_name()
    {
        return Utilities :: get_classname_from_namespace(self :: CLASS_NAME, true);
    }
}
