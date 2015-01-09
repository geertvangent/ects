<?php
namespace Chamilo\Application\Atlantis\application\right\table\right;

use libraries\format\table\extension\data_class_table\DataClassTableCellRenderer;
use libraries\format\TableCellRendererActionsColumnSupport;
use libraries\format\structure\Toolbar;
use libraries\format\theme\Theme;
use libraries\platform\translation\Translation;
use libraries\utilities\Utilities;
use libraries\format\structure\ToolbarItem;

class RightTableCellRenderer extends DataClassTableCellRenderer implements TableCellRendererActionsColumnSupport
{

    public function get_actions($right)
    {
        $toolbar = new Toolbar();
        if ($this->get_component()->get_user()->is_platform_admin())
        {
            $toolbar->add_item(
                new ToolbarItem(
                    Translation :: get('Edit', null, Utilities :: COMMON_LIBRARIES),
                    Theme :: get_common_image_path() . 'action_edit.png',
                    $this->get_component()->get_url(
                        array(
                            Manager :: PARAM_ACTION => Manager :: ACTION_EDIT,
                            Manager :: PARAM_RIGHT_ID => $right->get_id())),
                    ToolbarItem :: DISPLAY_ICON));
            $toolbar->add_item(
                new ToolbarItem(
                    Translation :: get('Delete', null, Utilities :: COMMON_LIBRARIES),
                    Theme :: get_common_image_path() . 'action_delete.png',
                    $this->get_component()->get_url(
                        array(
                            Manager :: PARAM_ACTION => Manager :: ACTION_DELETE,
                            Manager :: PARAM_RIGHT_ID => $right->get_id())),
                    ToolbarItem :: DISPLAY_ICON));
        }
        $toolbar->add_item(
            new ToolbarItem(
                Translation :: get('TypeName', null, '\application\atlantis\role\entitlement'),
                Theme :: get_image_path('\application\atlantis\role\entitlement') . 'logo/16.png',
                $this->get_component()->get_url(
                    array(
                        \application\atlantis\Manager :: PARAM_ACTION => \application\atlantis\Manager :: ACTION_ROLE,
                        \application\atlantis\role\Manager :: PARAM_ACTION => \application\atlantis\role\Manager :: ACTION_ENTITLEMENT,
                        \application\atlantis\role\entitlement\Manager :: PARAM_ACTION => \application\atlantis\role\entitlement\Manager :: ACTION_BROWSE,
                        Manager :: PARAM_RIGHT_ID => $right->get_id())),
                ToolbarItem :: DISPLAY_ICON));

        return $toolbar->as_html();
    }
}
