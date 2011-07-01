<?php
namespace application\discovery;

use common\libraries;

use common\libraries\Translation;
use common\libraries\Utilities;
use common\libraries\ObjectTableCellRenderer;
use common\libraries\Theme;
use common\libraries\DatetimeUtilities;

class DefaultDiscoveryModuleInstanceTableCellRenderer extends ObjectTableCellRenderer
{

    function __construct()
    {
    }

    function render_cell($column, $discovery_module_instance)
    {
        switch ($column->get_name())
        {
            case DiscoveryModuleInstance :: PROPERTY_ID :
                return $discovery_module_instance->get_id();
//            case DiscoveryModuleInstance :: PROPERTY_TYPE :
//                $name = htmlentities(Translation :: get('TypeName', null, DiscoveryModuleInstanceManager :: get_namespace($discovery_module_instance->get_instance_type(), $discovery_module_instance->get_type())));
//                return '<img src="' . Theme :: get_image_path(DiscoveryModuleInstanceManager :: get_namespace($discovery_module_instance->get_instance_type(), $discovery_module_instance->get_type())) . '/logo/22.png" alt="' . $name . '" title="' . $name . '"/>';
            case DiscoveryModuleInstance :: PROPERTY_TITLE :
                return Utilities :: truncate_string($discovery_module_instance->get_title(), 50);
            case DiscoveryModuleInstance :: PROPERTY_DESCRIPTION :
                return Utilities :: truncate_string($discovery_module_instance->get_description(), 50);
            default :
                return '&nbsp;';
        }
    }

    function render_id_cell($object)
    {
        return $object->get_id();
    }
}
?>