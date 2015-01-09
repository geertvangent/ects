<?php
namespace Chamilo\Application\Atlantis\user_group\table\application;

use libraries\utilities\Utilities;
use libraries\format\theme\Theme;
use libraries\platform\translation\Translation;
use libraries\format\structure\ToolbarItem;
use libraries\format\table\extension\data_class_table\DataClassTableCellRenderer;
use libraries\format\TableCellRendererActionsColumnSupport;
use libraries\format\structure\Toolbar;

class ApplicationTableCellRenderer extends DataClassTableCellRenderer implements TableCellRendererActionsColumnSupport
{

    public function get_actions($application)
    {
        $toolbar = new Toolbar();

        $toolbar->add_item(
            new ToolbarItem(
                Translation :: get('Edit', null, Utilities :: COMMON_LIBRARIES),
                Theme :: get_common_image_path() . 'action_edit.png',
                $this->get_component()->get_url(
                    array(
                        Manager :: PARAM_ACTION => Manager :: ACTION_EDIT,
                        Manager :: PARAM_APPLICATION_ID => $application->get_id())),
                ToolbarItem :: DISPLAY_ICON));
        $toolbar->add_item(
            new ToolbarItem(
                Translation :: get('Delete', null, Utilities :: COMMON_LIBRARIES),
                Theme :: get_common_image_path() . 'action_delete.png',
                $this->get_component()->get_url(
                    array(
                        Manager :: PARAM_ACTION => Manager :: ACTION_DELETE,
                        Manager :: PARAM_APPLICATION_ID => $application->get_id())),
                ToolbarItem :: DISPLAY_ICON));
        $toolbar->add_item(
            new ToolbarItem(
                Translation :: get('ManageRight', null, Utilities :: COMMON_LIBRARIES),
                Theme :: get_common_image_path() . 'action_rights.png',
                $this->get_component()->get_url(
                    array(
                        Manager :: PARAM_ACTION => Manager :: ACTION_MANAGE_RIGHT,
                        Manager :: PARAM_APPLICATION_ID => $application->get_id())),
                ToolbarItem :: DISPLAY_ICON));

        return $toolbar->as_html();
    }
}
