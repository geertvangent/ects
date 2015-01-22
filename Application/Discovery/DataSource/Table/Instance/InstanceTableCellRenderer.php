<?php
namespace Chamilo\Application\Discovery\DataSource\Table\Instance;

use Chamilo\Application\Discovery\DataSource\DataClass\Instance;
use Chamilo\Application\Discovery\DataSource\Manager;
use Chamilo\Libraries\Format\Structure\Toolbar;
use Chamilo\Libraries\Format\Structure\ToolbarItem;
use Chamilo\Libraries\Format\Table\Extension\DataClassTable\DataClassTableCellRenderer;
use Chamilo\Libraries\Format\Table\Interfaces\TableCellRendererActionsColumnSupport;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Utilities\Utilities;

class InstanceTableCellRenderer extends DataClassTableCellRenderer implements TableCellRendererActionsColumnSupport
{

    public function render_cell($column, $module_instance)
    {
        switch ($column->get_name())
        {

            case Instance :: PROPERTY_TYPE :
                $name = htmlentities(Translation :: get('TypeName', null, $module_instance->get_type()));
                return '<img src="' . Theme :: getInstance()->getImagePath($module_instance->get_type()) . '/logo/22.png" alt="' .
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
                Theme :: getInstance()->getCommonImagePath() . 'action_edit.png',
                $this->get_component()->get_url(
                    array(
                        Manager :: PARAM_ACTION => Manager :: ACTION_UPDATE_INSTANCE,
                        \Chamilo\Application\Discovery\Manager :: PARAM_MODULE_ID => $module_instance->get_id())),
                ToolbarItem :: DISPLAY_ICON));
        $toolbar->add_item(
            new ToolbarItem(
                Translation :: get('Delete', null, Utilities :: COMMON_LIBRARIES),
                Theme :: getInstance()->getCommonImagePath() . 'action_delete.png',
                $this->get_component()->get_url(
                    array(
                        Manager :: PARAM_ACTION => Manager :: ACTION_DELETE_INSTANCE,
                        \Chamilo\Application\Discovery\Manager :: PARAM_MODULE_ID => $module_instance->get_id())),
                ToolbarItem :: DISPLAY_ICON,
                true));
        return $toolbar->as_html();
    }
}
