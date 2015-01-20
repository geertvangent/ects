<?php
namespace Ehb\Application\Atlantis\UserGroup\Table\Application;

use Chamilo\Libraries\Utilities\Utilities;
use Chamilo\Libraries\Format\Theme\Theme;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Format\Structure\ToolbarItem;
use Chamilo\Libraries\Format\Table\Extension\DataClassTable\DataClassTableCellRenderer;
use Chamilo\Libraries\Format\Table\Interfaces\TableCellRendererActionsColumnSupport;
use Chamilo\Libraries\Format\Structure\Toolbar;
use Ehb\Application\Atlantis\UserGroup\Manager;

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
