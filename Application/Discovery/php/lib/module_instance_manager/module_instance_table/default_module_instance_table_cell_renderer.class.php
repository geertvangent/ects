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
            case ModuleInstance :: PROPERTY_TYPE :
                $name = htmlentities(Translation :: get('TypeName', null, $module_instance->get_type()));
                return '<img src="' . Theme :: get_image_path($module_instance->get_type()) . '/logo/22.png" alt="' . $name . '" title="' . $name . '"/>';
            case ModuleInstance :: PROPERTY_TITLE :
                return Translation :: get('TypeName', null, $module_instance->get_type());
            case ModuleInstance :: PROPERTY_DESCRIPTION :
                return Utilities :: truncate_string(
                        Translation :: get('TypeDescription', null, $module_instance->get_type()), 50);
            case ModuleInstance :: PROPERTY_DISPLAY_ORDER :
                return $module_instance->get_display_order();
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