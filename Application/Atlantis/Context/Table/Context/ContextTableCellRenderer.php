<?php
namespace Ehb\Application\Atlantis\Context\Table\Context;

use Chamilo\Libraries\Format\Structure\Toolbar;
use Chamilo\Libraries\Format\Structure\ToolbarItem;
use Chamilo\Libraries\Format\Table\Extension\DataClassTable\DataClassTableCellRenderer;
use Chamilo\Libraries\Format\Table\Interfaces\TableCellRendererActionsColumnSupport;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Translation;
use Ehb\Application\Atlantis\Context\Manager;

class ContextTableCellRenderer extends DataClassTableCellRenderer implements TableCellRendererActionsColumnSupport
{

    public function get_actions($object)
    {
        $toolbar = new Toolbar();
        
        $toolbar->add_item(
            new ToolbarItem(
                Translation :: get('TypeName', null, '\application\atlantis\role\entity'), 
                Theme :: getInstance()->getImagePath('\application\atlantis\role\entity') . 'logo/16.png', 
                $this->get_component()->get_url(
                    array(
                        \Ehb\Application\Atlantis\Manager :: PARAM_ACTION => \Ehb\Application\Atlantis\Manager :: ACTION_ROLE, 
                        \Ehb\Application\Atlantis\Role\Manager :: PARAM_ACTION => \Ehb\Application\Atlantis\Role\Manager :: ACTION_ENTITY, 
                        \Ehb\Application\Atlantis\Role\Entity\Manager :: PARAM_ACTION => \Ehb\Application\Atlantis\Role\Entity\Manager :: ACTION_BROWSE, 
                        Manager :: PARAM_CONTEXT_ID => $object->get_id())), 
                ToolbarItem :: DISPLAY_ICON));
        
        return $toolbar->as_html();
    }
}
