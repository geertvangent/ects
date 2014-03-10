<?php
namespace application\discovery\instance;

use common\libraries\EqualityCondition;
use common\libraries\Translation;
use common\libraries\Utilities;
use common\libraries\ToolbarItem;
use common\libraries\Toolbar;
use common\libraries\Theme;
use common\libraries\DataClassCountParameters;

class InstanceBrowserTableCellRenderer extends DefaultInstanceTableCellRenderer
{

    private $browser;

    public function __construct($browser)
    {
        parent :: __construct();
        $this->browser = $browser;
    }

    public function render_cell($column, $module_instance)
    {
        if ($column === InstanceBrowserTableColumnModel :: get_modification_column())
        {
            return $this->get_modification_links($module_instance);
        }

        return parent :: render_cell($column, $module_instance);
    }

    private function get_modification_links($module_instance)
    {
        $toolbar = new Toolbar();

        $allowed = $this->check_move_allowed($module_instance);

        if ($this->is_display_order_column())
        {
            if ($allowed["moveup"])
            {
                $toolbar->add_item(
                    new ToolbarItem(
                        Translation :: get('MoveUp', null, Utilities :: COMMON_LIBRARIES),
                        Theme :: get_common_image_path() . 'action_up.png',
                        $this->browser->get_url(
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
                        $this->browser->get_url(
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
                    $this->browser->get_url(
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
                    $this->browser->get_url(
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
                $this->browser->get_url(
                    array(
                        Manager :: PARAM_ACTION => Manager :: ACTION_UPDATE_INSTANCE,
                        \application\discovery\Manager :: PARAM_MODULE_ID => $module_instance->get_id())),
                ToolbarItem :: DISPLAY_ICON));
        $toolbar->add_item(
            new ToolbarItem(
                Translation :: get('Delete', null, Utilities :: COMMON_LIBRARIES),
                Theme :: get_common_image_path() . 'action_delete.png',
                $this->browser->get_url(
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
                new EqualityCondition(Instance :: PROPERTY_CONTENT_TYPE, $module_instance->get_content_type())));

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
