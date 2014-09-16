<?php
namespace application\discovery\data_source;

use libraries\Translation;
use libraries\Utilities;
use libraries\ObjectTableCellRenderer;
use libraries\Theme;

class DefaultInstanceTableCellRenderer extends ObjectTableCellRenderer
{

    public function __construct()
    {
    }

    public function render_cell($column, $module_instance)
    {
        switch ($column->get_name())
        {
            case Instance :: PROPERTY_ID :
                return $module_instance->get_id();
            case Instance :: PROPERTY_TYPE :
                $name = htmlentities(Translation :: get('TypeName', null, $module_instance->get_type()));
                return '<img src="' . Theme :: get_image_path($module_instance->get_type()) . '/logo/22.png" alt="' .
                     $name . '" title="' . $name . '"/>';
            case Instance :: PROPERTY_NAME :
                return Translation :: get('TypeName', null, $module_instance->get_type());
            case Instance :: PROPERTY_DESCRIPTION :
                return Utilities :: truncate_string(
                    Translation :: get('TypeDescription', null, $module_instance->get_type()), 
                    50);
            default :
                return '&nbsp;';
        }
    }

    public function render_id_cell($object)
    {
        return $object->get_id();
    }
}
