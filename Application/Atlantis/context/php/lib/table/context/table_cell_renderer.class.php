<?php
namespace application\atlantis\context;

use common\libraries\Utilities;
use common\libraries\Theme;
use common\libraries\Translation;
use common\libraries\ToolbarItem;
use common\libraries\NewObjectTableCellRenderer;
use common\libraries\NewObjectTableCellRendererActionsColumnSupport;
use common\libraries\Toolbar;

class ApplicationTableCellRenderer extends NewObjectTableCellRenderer implements 
    NewObjectTableCellRendererActionsColumnSupport
{

    public function render_cell($column, $object)
    {
        dump($object);
        switch ($column->get_name())
        {
            case 'entity_name' :
                return $object->get_role()->get_entity()->get_entity_name();
                break;
            case \application\atlantis\context\Context :: PROPERTY_CONTEXT_NAME :
                return $object->get_context()->get_context_name();
                break;
            
            case \application\atlantis\role\Role :: PROPERTY_NAME :
                return $object->get_role()->get_name();
                break;
        }
        return parent :: render_cell($column, $object);
    }

    public function get_object_actions($application)
    {
        $toolbar = new Toolbar();
        
        $toolbar->add_item(
            new ToolbarItem(
                Translation :: get('Delete', null, Utilities :: COMMON_LIBRARIES), 
                Theme :: get_common_image_path() . 'action_delete.png', 
                $this->get_component()->get_url(
                    array(
                        Manager :: PARAM_ACTION => Manager :: ACTION_DELETE, 
                        Manager :: PARAM_APPLICATION_ID => $application->get_id())), 
                ToolbarItem :: DISPLAY_ICON));
        
        return $toolbar->as_html();
    }
}
