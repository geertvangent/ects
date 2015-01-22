<?php
namespace Ehb\Application\Atlantis\Role\Entitlement\Table\Entitlement;

use Chamilo\Libraries\Format\Structure\Toolbar;
use Chamilo\Libraries\Format\Structure\ToolbarItem;
use Chamilo\Libraries\Format\Table\Extension\DataClassTable\DataClassTableCellRenderer;
use Chamilo\Libraries\Format\Table\Interfaces\TableCellRendererActionsColumnSupport;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Utilities\Utilities;
use Ehb\Application\Atlantis\Role\Entitlement\Manager;

class EntitlementTableCellRenderer extends DataClassTableCellRenderer implements TableCellRendererActionsColumnSupport
{

    public function render_cell($column, $object)
    {
        switch ($column->get_name())
        {
            case Translation :: get('TypeName', null, '\application\atlantis\role') :
                return $object->get_role()->get_name();
                break;
            case Translation :: get('TypeName', null, '\application\atlantis\application') :
                return $object->get_right()->get_application()->get_name();
                break;
            case Translation :: get('TypeName', null, '\application\atlantis\application\right') :
                return $object->get_right()->get_name();
                break;
        }

        return parent :: render_cell($column, $object);
    }

    public function get_actions($entitlement)
    {
        $toolbar = new Toolbar();
        if ($this->get_component()->get_user()->is_platform_admin())
        {
            $toolbar->add_item(
                new ToolbarItem(
                    Translation :: get('Delete', null, Utilities :: COMMON_LIBRARIES),
                    Theme :: getInstance()->getCommonImagePath() . 'action_delete.png',
                    $this->get_component()->get_url(
                        array(
                            Manager :: PARAM_ACTION => Manager :: ACTION_DELETE,
                            Manager :: PARAM_ENTITLEMENT_ID => $entitlement->get_id())),
                    ToolbarItem :: DISPLAY_ICON));
        }
        return $toolbar->as_html();
    }
}
