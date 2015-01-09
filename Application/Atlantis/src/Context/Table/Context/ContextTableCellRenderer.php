<?php
namespace Chamilo\Application\Atlantis\Context\Table\Context;

use Chamilo\Libraries\Format\Theme\Theme;
use Chamilo\Libraries\Platform\Translation\Translation;
use Chamilo\Libraries\Format\Structure\ToolbarItem;
use Chamilo\Libraries\Format\Structure\Toolbar;
use Chamilo\Libraries\Format\TableCellRendererActionsColumnSupport;
use Chamilo\Libraries\Format\Table\Extension\DataClassTable\DataClassTableCellRenderer;

class ContextTableCellRenderer extends DataClassTableCellRenderer implements TableCellRendererActionsColumnSupport
{

    public function get_actions($object)
    {
        $toolbar = new Toolbar();

        $toolbar->add_item(
            new ToolbarItem(
                Translation :: get('TypeName', null, '\application\atlantis\role\entity'),
                Theme :: get_image_path('\application\atlantis\role\entity') . 'logo/16.png',
                $this->get_component()->get_url(
                    array(
                        \Chamilo\Application\Atlantis\Manager :: PARAM_ACTION => \Chamilo\Application\Atlantis\Manager :: ACTION_ROLE,
                        \Chamilo\Application\Atlantis\Role\Manager :: PARAM_ACTION => \Chamilo\Application\Atlantis\Role\Manager :: ACTION_ENTITY,
                        \Chamilo\Application\Atlantis\Role\Entity\Manager :: PARAM_ACTION => \Chamilo\Application\Atlantis\Role\Entity\Manager :: ACTION_BROWSE,
                        Manager :: PARAM_CONTEXT_ID => $object->get_id())),
                ToolbarItem :: DISPLAY_ICON));

        return $toolbar->as_html();
    }
}
