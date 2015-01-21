<?php
namespace Ehb\Application\Atlantis\Application\Right\Table\Right;

use Chamilo\Libraries\Format\Table\Extension\DataClassTable\DataClassTableCellRenderer;
use Chamilo\Libraries\Format\Table\Interfaces\TableCellRendererActionsColumnSupport;
use Chamilo\Libraries\Format\Structure\Toolbar;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Utilities\Utilities;
use Chamilo\Libraries\Format\Structure\ToolbarItem;
use Ehb\Application\Atlantis\Application\Right\Manager;

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
                        \Ehb\Application\Atlantis\Manager :: PARAM_ACTION => \Ehb\Application\Atlantis\Manager :: ACTION_ROLE,
                        \Ehb\Application\Atlantis\Role\Manager :: PARAM_ACTION => \Ehb\Application\Atlantis\Role\Manager :: ACTION_ENTITLEMENT,
                        \Ehb\Application\Atlantis\Role\Entitlement\Manager :: PARAM_ACTION => \Ehb\Application\Atlantis\Role\Entitlement\Manager :: ACTION_BROWSE,
                        Manager :: PARAM_RIGHT_ID => $right->get_id())),
                ToolbarItem :: DISPLAY_ICON));

        return $toolbar->as_html();
    }
}
