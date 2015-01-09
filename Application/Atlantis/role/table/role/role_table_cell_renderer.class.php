<?php
namespace Chamilo\Application\Atlantis\role\table\role;

use libraries\architecture\application\Application;
use libraries\format\theme\Theme;
use libraries\platform\translation\Translation;
use libraries\utilities\Utilities;
use libraries\format\structure\ToolbarItem;
use libraries\format\table\extension\data_class_table\DataClassTableCellRenderer;
use libraries\format\TableCellRendererActionsColumnSupport;
use libraries\format\structure\Toolbar;

class RoleTableCellRenderer extends DataClassTableCellRenderer implements TableCellRendererActionsColumnSupport
{

    public function get_actions($role)
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
                            Manager :: PARAM_ROLE_ID => $role->get_id())),
                    ToolbarItem :: DISPLAY_ICON));
            $toolbar->add_item(
                new ToolbarItem(
                    Translation :: get('Delete', null, Utilities :: COMMON_LIBRARIES),
                    Theme :: get_common_image_path() . 'action_delete.png',
                    $this->get_component()->get_url(
                        array(
                            Manager :: PARAM_ACTION => Manager :: ACTION_DELETE,
                            Manager :: PARAM_ROLE_ID => $role->get_id())),
                    ToolbarItem :: DISPLAY_ICON));
            $toolbar->add_item(
                new ToolbarItem(
                    Translation :: get('List'),
                    Theme :: get_image_path() . 'list.png',
                    $this->get_component()->get_url(
                        array(
                            Application :: PARAM_ACTION => \application\atlantis\Manager :: ACTION_ROLE,
                            \application\atlantis\role\Manager :: PARAM_ACTION => \application\atlantis\role\Manager :: ACTION_ENTITLEMENT,
                            Manager :: PARAM_ROLE_ID => $role->get_id())),
                    ToolbarItem :: DISPLAY_ICON));
        }
        $toolbar->add_item(
            new ToolbarItem(
                Translation :: get('RoleEntity'),
                Theme :: get_image_path(__NAMESPACE__ . '\entity') . 'logo/16.png',
                $this->get_component()->get_url(
                    array(
                        Application :: PARAM_ACTION => \application\atlantis\Manager :: ACTION_ROLE,
                        \application\atlantis\role\Manager :: PARAM_ACTION => \application\atlantis\role\Manager :: ACTION_ENTITY,
                        \application\atlantis\role\entity\Manager :: PARAM_ACTION => \application\atlantis\role\entity\Manager :: ACTION_BROWSE,
                        Manager :: PARAM_ROLE_ID => $role->get_id())),
                ToolbarItem :: DISPLAY_ICON));
        $toolbar->add_item(
            new ToolbarItem(
                Translation :: get('TypeName', null, '\application\atlantis\role\entitlement'),
                Theme :: get_image_path('\application\atlantis\role\entitlement') . 'logo/16.png',
                $this->get_component()->get_url(
                    array(
                        \application\atlantis\Manager :: PARAM_ACTION => \application\atlantis\Manager :: ACTION_ROLE,
                        \application\atlantis\role\Manager :: PARAM_ACTION => \application\atlantis\role\Manager :: ACTION_ENTITLEMENT,
                        \application\atlantis\role\entitlement\Manager :: PARAM_ACTION => \application\atlantis\role\entitlement\Manager :: ACTION_BROWSE,
                        Manager :: PARAM_ROLE_ID => $role->get_id())),
                ToolbarItem :: DISPLAY_ICON));

        return $toolbar->as_html();
    }
}
