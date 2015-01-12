<?php
namespace application\discovery\data_source;

use libraries\platform\translation\Translation;
use libraries\utilities\Utilities;
use libraries\format\structure\ToolbarItem;
use libraries\format\structure\Toolbar;
use libraries\format\theme\Theme;
use libraries\format\TableCellRendererActionsColumnSupport;
use libraries\format\table\extension\data_class_table\DataClassTableCellRenderer;

class InstanceTableCellRenderer extends DataClassTableCellRenderer implements TableCellRendererActionsColumnSupport
{

    public function render_cell($column, $module_instance)
    {
        switch ($column->get_name())
        {

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
        }
        return parent :: render_cell($column, $module_instance);
    }

    public function get_actions($module_instance)
    {
        $toolbar = new Toolbar();

        $toolbar->add_item(
            new ToolbarItem(
                Translation :: get('Edit', null, Utilities :: COMMON_LIBRARIES),
                Theme :: get_common_image_path() . 'action_edit.png',
                $this->get_component()->get_url(
                    array(
                        Manager :: PARAM_ACTION => Manager :: ACTION_UPDATE_INSTANCE,
                        \application\discovery\Manager :: PARAM_MODULE_ID => $module_instance->get_id())),
                ToolbarItem :: DISPLAY_ICON));
        $toolbar->add_item(
            new ToolbarItem(
                Translation :: get('Delete', null, Utilities :: COMMON_LIBRARIES),
                Theme :: get_common_image_path() . 'action_delete.png',
                $this->get_component()->get_url(
                    array(
                        Manager :: PARAM_ACTION => Manager :: ACTION_DELETE_INSTANCE,
                        \application\discovery\Manager :: PARAM_MODULE_ID => $module_instance->get_id())),
                ToolbarItem :: DISPLAY_ICON,
                true));
        return $toolbar->as_html();
    }
}
