<?php
namespace Ehb\Application\Atlantis\Role\Table\Role;

use Chamilo\Libraries\Architecture\Application\Application;
use Chamilo\Libraries\Format\Theme\Theme;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Utilities\Utilities;
use Chamilo\Libraries\Format\Structure\ToolbarItem;
use Chamilo\Libraries\Format\Table\Extension\DataClassTable\DataClassTableCellRenderer;
use Chamilo\Libraries\Format\Table\Interfaces\TableCellRendererActionsColumnSupport;
use Chamilo\Libraries\Format\Structure\Toolbar;
use Ehb\Application\Atlantis\Role\Manager;

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
                            Application :: PARAM_ACTION => \Ehb\Application\Atlantis\Manager :: ACTION_ROLE,
                            \Ehb\Application\Atlantis\Role\Manager :: PARAM_ACTION => \Ehb\Application\Atlantis\Role\Manager :: ACTION_ENTITLEMENT,
                            Manager :: PARAM_ROLE_ID => $role->get_id())),
                    ToolbarItem :: DISPLAY_ICON));
        }
        $toolbar->add_item(
            new ToolbarItem(
                Translation :: get('RoleEntity'),
                Theme :: get_image_path(__NAMESPACE__ . '\entity') . 'logo/16.png',
                $this->get_component()->get_url(
                    array(
                        Application :: PARAM_ACTION => \Ehb\Application\Atlantis\Manager :: ACTION_ROLE,
                        \Ehb\Application\Atlantis\Role\Manager :: PARAM_ACTION => \Ehb\Application\Atlantis\Role\Manager :: ACTION_ENTITY,
                        \Ehb\Application\Atlantis\Role\Entity\Manager :: PARAM_ACTION => \Ehb\Application\Atlantis\Role\Entity\Manager :: ACTION_BROWSE,
                        Manager :: PARAM_ROLE_ID => $role->get_id())),
                ToolbarItem :: DISPLAY_ICON));
        $toolbar->add_item(
            new ToolbarItem(
                Translation :: get('TypeName', null, '\application\atlantis\role\entitlement'),
                Theme :: get_image_path('\application\atlantis\role\entitlement') . 'logo/16.png',
                $this->get_component()->get_url(
                    array(
                        \Ehb\Application\Atlantis\Manager :: PARAM_ACTION => \Ehb\Application\Atlantis\Manager :: ACTION_ROLE,
                        \Ehb\Application\Atlantis\Role\Manager :: PARAM_ACTION => \Ehb\Application\Atlantis\Role\Manager :: ACTION_ENTITLEMENT,
                        \Ehb\Application\Atlantis\Role\Entitlement\Manager :: PARAM_ACTION => \Ehb\Application\Atlantis\Role\Entitlement\Manager :: ACTION_BROWSE,
                        Manager :: PARAM_ROLE_ID => $role->get_id())),
                ToolbarItem :: DISPLAY_ICON));

        return $toolbar->as_html();
    }
}
