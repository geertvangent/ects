<?php
namespace application\atlantis\application;

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
        switch ($column->get_name())
        {
            case Application :: PROPERTY_NAME :
                $link = $this->get_component()->get_url(array(Manager :: PARAM_ACTION => Manager :: ACTION_VIEW, 
                        Manager :: PARAM_APPLICATION_ID => $object->get_id()));
                return '<a href="' . $link . '">' . parent :: render_cell($column, $object) . '</a>';
                break;
        }
        return parent :: render_cell($column, $object);
    }

    function get_object_actions($application)
    {
        $toolbar = new Toolbar();
        
        $toolbar->add_item(new ToolbarItem(Translation :: get('Edit', null, Utilities :: COMMON_LIBRARIES), Theme :: get_common_image_path() . 'action_edit.png', $this->get_component()->get_url(array(
                Manager :: PARAM_ACTION => Manager :: ACTION_EDIT, 
                Manager :: PARAM_APPLICATION_ID => $application->get_id())), ToolbarItem :: DISPLAY_ICON));
        $toolbar->add_item(new ToolbarItem(Translation :: get('Delete', null, Utilities :: COMMON_LIBRARIES), Theme :: get_common_image_path() . 'action_delete.png', $this->get_component()->get_url(array(
                Manager :: PARAM_ACTION => Manager :: ACTION_DELETE, 
                Manager :: PARAM_APPLICATION_ID => $application->get_id())), ToolbarItem :: DISPLAY_ICON));
        $toolbar->add_item(new ToolbarItem(Translation :: get('ManageRight', null, Utilities :: COMMON_LIBRARIES), Theme :: get_common_image_path() . 'action_rights.png', $this->get_component()->get_url(array(
                Manager :: PARAM_ACTION => Manager :: ACTION_MANAGE_RIGHT, 
                Manager :: PARAM_APPLICATION_ID => $application->get_id())), ToolbarItem :: DISPLAY_ICON));
        
        return $toolbar->as_html();
    }
}
?>
