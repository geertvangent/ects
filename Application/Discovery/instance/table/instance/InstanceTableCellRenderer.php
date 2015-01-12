<?php
namespace Application\Discovery\instance\table\instance;

use libraries\storage\EqualityCondition;
use libraries\platform\translation\Translation;
use libraries\utilities\Utilities;
use libraries\format\structure\ToolbarItem;
use libraries\format\structure\Toolbar;
use libraries\format\theme\Theme;
use libraries\storage\DataClassCountParameters;
use libraries\storage\StaticConditionVariable;
use libraries\storage\PropertyConditionVariable;
use libraries\format\TableCellRendererActionsColumnSupport;
use libraries\format\table\extension\data_class_table\DataClassTableCellRenderer;
use libraries\format\DisplayOrderPropertyTableColumn;

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
            case Instance :: PROPERTY_TITLE :
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

        $allowed = $this->check_move_allowed($module_instance);

        if ($this->is_order_column_type(DisplayOrderPropertyTableColumn :: class_name()))
        {
            if ($allowed["moveup"])
            {
                $toolbar->add_item(
                    new ToolbarItem(
                        Translation :: get('MoveUp', null, Utilities :: COMMON_LIBRARIES),
                        Theme :: get_common_image_path() . 'action_up.png',
                        $this->get_component()->get_url(
                            array(
                                Manager :: PARAM_ACTION => Manager :: ACTION_MOVE_INSTANCE,
                                \application\discovery\Manager :: PARAM_MODULE_ID => $module_instance->get_id(),
                                \application\discovery\Manager :: PARAM_DIRECTION => \application\discovery\Manager :: PARAM_DIRECTION_UP)),
                        ToolbarItem :: DISPLAY_ICON));
            }
            else
            {
                $toolbar->add_item(
                    new ToolbarItem(
                        Translation :: get('MoveUpNotAvailable', null, Utilities :: COMMON_LIBRARIES),
                        Theme :: get_common_image_path() . 'action_up_na.png',
                        null,
                        ToolbarItem :: DISPLAY_ICON));
            }

            if ($allowed["movedown"])
            {
                $toolbar->add_item(
                    new ToolbarItem(
                        Translation :: get('MoveDown', null, Utilities :: COMMON_LIBRARIES),
                        Theme :: get_common_image_path() . 'action_down.png',
                        $this->get_component()->get_url(
                            array(
                                Manager :: PARAM_ACTION => Manager :: ACTION_MOVE_INSTANCE,
                                \application\discovery\Manager :: PARAM_MODULE_ID => $module_instance->get_id(),
                                \application\discovery\Manager :: PARAM_DIRECTION => \application\discovery\Manager :: PARAM_DIRECTION_DOWN)),
                        ToolbarItem :: DISPLAY_ICON));
            }
            else
            {
                $toolbar->add_item(
                    new ToolbarItem(
                        Translation :: get('MoveDownNotAvailable', null, Utilities :: COMMON_LIBRARIES),
                        Theme :: get_common_image_path() . 'action_down_na.png',
                        null,
                        ToolbarItem :: DISPLAY_ICON));
            }
        }

        if ($module_instance->is_enabled())
        {
            $toolbar->add_item(
                new ToolbarItem(
                    Translation :: get('Deactivate', null, Utilities :: COMMON_LIBRARIES),
                    Theme :: get_common_image_path() . 'action_deactivate.png',
                    $this->get_component()->get_url(
                        array(
                            Manager :: PARAM_ACTION => Manager :: ACTION_DEACTIVATE_INSTANCE,
                            \application\discovery\Manager :: PARAM_MODULE_ID => $module_instance->get_id())),
                    ToolbarItem :: DISPLAY_ICON,
                    true));
        }
        else
        {
            $toolbar->add_item(
                new ToolbarItem(
                    Translation :: get('Activate', null, Utilities :: COMMON_LIBRARIES),
                    Theme :: get_common_image_path() . 'action_activate.png',
                    $this->get_component()->get_url(
                        array(
                            Manager :: PARAM_ACTION => Manager :: ACTION_ACTIVATE_INSTANCE,
                            \application\discovery\Manager :: PARAM_MODULE_ID => $module_instance->get_id())),
                    ToolbarItem :: DISPLAY_ICON,
                    true));
        }

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

    protected function check_move_allowed($module_instance)
    {
        $moveup_allowed = true;
        $movedown_allowed = true;

        $count = DataManager :: count(
            Instance :: class_name(),
            new DataClassCountParameters(
                new EqualityCondition(
                    new PropertyConditionVariable(Instance :: class_name(), Instance :: PROPERTY_CONTENT_TYPE),
                    new StaticConditionVariable($module_instance->get_content_type()))));

        if ($count == 1)
        {
            $moveup_allowed = false;
            $movedown_allowed = false;
        }
        else
        {
            if ($module_instance->get_display_order() == 1)
            {
                $moveup_allowed = false;
            }
            else
            {
                if ($module_instance->get_display_order() == $count)
                {
                    $movedown_allowed = false;
                }
            }
        }

        return array('moveup' => $moveup_allowed, 'movedown' => $movedown_allowed);
    }
}
