<?php
namespace application\discovery\module\assessment\implementation\chamilo\source\application;

use common\libraries\Utilities;
use common\libraries\DataClass;

/**
 * application.discovery.module.assessment.implementation.chamilo.source.application.discovery
 * @author Hans De Bisschop
 */
class Assessment extends DataClass
{
    const CLASS_NAME = __CLASS__;

    /**
     * Assessment properties
     */

    /**
     * Get the default properties
     * @param multitype:string $extended_property_names
     * @return multitype:string The property names.
     */
    static function get_default_property_names($extended_property_names = array())
    {

        return parent :: get_default_property_names($extended_property_names);
    }

    /**
     * @return string The table name of the data class
     */
    static function get_table_name()
    {
        return Utilities :: get_classname_from_namespace(self :: CLASS_NAME, true);
    }
}
