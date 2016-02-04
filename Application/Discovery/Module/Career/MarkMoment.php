<?php
namespace Ehb\Application\Discovery\Module\Career;

use Chamilo\Libraries\Storage\DataClass\DataClass;
use Chamilo\Libraries\Utilities\Utilities;

/**
 * application.discovery.module.career.discovery
 *
 * @author Hans De Bisschop
 */
class MarkMoment extends DataClass
{

    /**
     * Mark moment properties
     */
    const PROPERTY_NAME = 'name';

    /**
     * Get the default properties
     *
     * @param multitype:string $extended_property_names
     * @return multitype:string The property names.
     */
    public static function get_default_property_names($extended_property_names = array())
    {
        $extended_property_names[] = self :: PROPERTY_NAME;

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
     * Returns the name of this MarkMoment.
     *
     * @return string The name.
     */
    public function get_name()
    {
        return $this->get_default_property(self :: PROPERTY_NAME);
    }

    /**
     * Sets the name of this Mark.
     *
     * @param string $name
     */
    public function set_name($name)
    {
        $this->set_default_property(self :: PROPERTY_NAME, $name);
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
