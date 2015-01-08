<?php
namespace application\atlantis\context;

use libraries\format\Theme;
use libraries\platform\translation\Translation;
use libraries\format\ToolbarItem;
use libraries\format\Toolbar;
use libraries\format\TableCellRendererActionsColumnSupport;
use libraries\format\table\extension\data_class_table\DataClassTableCellRenderer;

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
                        \application\atlantis\Manager :: PARAM_ACTION => \application\atlantis\Manager :: ACTION_ROLE,
                        \application\atlantis\role\Manager :: PARAM_ACTION => \application\atlantis\role\Manager :: ACTION_ENTITY,
                        \application\atlantis\role\entity\Manager :: PARAM_ACTION => \application\atlantis\role\entity\Manager :: ACTION_BROWSE,
                        Manager :: PARAM_CONTEXT_ID => $object->get_id())),
                ToolbarItem :: DISPLAY_ICON));

        return $toolbar->as_html();
    }
}
