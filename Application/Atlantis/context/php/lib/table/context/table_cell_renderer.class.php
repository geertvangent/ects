<?php
namespace application\atlantis\context;

use libraries\Theme;
use libraries\Translation;
use libraries\ToolbarItem;
use libraries\Toolbar;
use libraries\NewObjectTableCellRendererActionsColumnSupport;
use libraries\NewObjectTableCellRenderer;

class ContextTableCellRenderer extends NewObjectTableCellRenderer implements 
        NewObjectTableCellRendererActionsColumnSupport
{

    public function get_object_actions($object)
    {
        $toolbar = new Toolbar();
        
        $toolbar->add_item(
                new ToolbarItem(Translation :: get('TypeName', null, '\application\atlantis\role\entity'), 
                        Theme :: get_image_path('\application\atlantis\role\entity') . 'logo/16.png', 
                        $this->get_component()->get_url(
                                array(
                                        \application\atlantis\Manager :: PARAM_ACTION => \application\atlantis\Manager :: ACTION_ROLE, 
                                        \application\atlantis\role\Manager :: PARAM_ACTION => \application\atlantis\role\Manager :: ACTION_ENTITY, 
                                        \application\atlantis\role\entity\Manager :: PARAM_ACTION => \application\atlantis\role\entity\Manager :: ACTION_BROWSE, 
                                        Manager :: PARAM_CONTEXT_ID => $object->get_id())), ToolbarItem :: DISPLAY_ICON));
        
        return $toolbar->as_html();
    }
}
