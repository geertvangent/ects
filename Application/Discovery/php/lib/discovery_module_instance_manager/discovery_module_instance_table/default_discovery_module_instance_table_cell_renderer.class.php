<?php
namespace application\discovery;

use common\libraries;

use common\libraries\Translation;
use common\libraries\Utilities;
use common\libraries\ObjectTableCellRenderer;
use common\libraries\Theme;
use common\libraries\DatetimeUtilities;

class DefaultModuleInstanceTableCellRenderer extends ObjectTableCellRenderer
{

    function __construct()
    {
    }

    function render_cell($column, $module_instance)
    {
        switch ($column->get_name())
        {
            case ModuleInstance :: PROPERTY_ID :
                return $module_instance->get_id();
//            case ModuleInstance :: PROPERTY_TYPE :
//                $name = htmlentities(Translation :: get('TypeName', null, ModuleInstanceManager :: get_namespace($module_instance->get_instance_type(), $module_instance->get_type())));
//                return '<img src="' . Theme :: get_image_path(ModuleInstanceManager :: get_namespace($module_instance->get_instance_type(), $module_instance->get_type())) . '/logo/22.png" alt="' . $name . '" title="' . $name . '"/>';
            case ModuleInstance :: PROPERTY_TITLE :
                return Utilities :: truncate_string($module_instance->get_title(), 50);
            case ModuleInstance :: PROPERTY_DESCRIPTION :
                return Utilities :: truncate_string($module_instance->get_description(), 50);
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