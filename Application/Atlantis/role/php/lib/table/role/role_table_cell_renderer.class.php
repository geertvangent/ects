<?php
namespace application\atlantis\role;

use common\libraries\Application;

use common\libraries\Theme;
use common\libraries\Translation;
use common\libraries\Utilities;
use common\libraries\ToolbarItem;
use common\libraries\NewObjectTableCellRenderer;
use common\libraries\NewObjectTableCellRendererActionsColumnSupport;
use common\libraries\Toolbar;

class RoleTableCellRenderer extends NewObjectTableCellRenderer implements NewObjectTableCellRendererActionsColumnSupport
{

    public function render_cell($column, $object)
    {
        switch ($column->get_name())
        {
            case Role :: PROPERTY_NAME :
                $link = $this->get_component()->get_url(array(Manager :: PARAM_ACTION => Manager :: ACTION_VIEW, 
                        Manager :: PARAM_ROLE_ID => $object->get_id()));
                return '<a href="' . $link . '">' . parent :: render_cell($column, $object) . '</a>';
                break;
        }
        return parent :: render_cell($column, $object);
    }

    function get_object_actions($role)
    {
        $toolbar = new Toolbar();
        
        $toolbar->add_item(new ToolbarItem(Translation :: get('Edit', null, Utilities :: COMMON_LIBRARIES), Theme :: get_common_image_path() . 'action_edit.png', $this->get_component()->get_url(array(
                Manager :: PARAM_ACTION => Manager :: ACTION_EDIT, Manager :: PARAM_ROLE_ID => $role->get_id())), ToolbarItem :: DISPLAY_ICON));
        $toolbar->add_item(new ToolbarItem(Translation :: get('Delete', null, Utilities :: COMMON_LIBRARIES), Theme :: get_common_image_path() . 'action_delete.png', $this->get_component()->get_url(array(
                Manager :: PARAM_ACTION => Manager :: ACTION_DELETE, Manager :: PARAM_ROLE_ID => $role->get_id())), ToolbarItem :: DISPLAY_ICON));
        $toolbar->add_item(new ToolbarItem(Translation :: get('List', null, Utilities :: COMMON_LIBRARIES), Theme :: get_common_image_path() . 'action_list.png', $this->get_component()->get_url(array(
                Application :: PARAM_ACTION => \application\atlantis\Manager :: ACTION_ROLE, 
                \application\atlantis\role\Manager :: PARAM_ACTION => \application\atlantis\role\Manager :: ACTION_ENTITLEMENT, 
                Manager :: PARAM_ROLE_ID => $role->get_id())), ToolbarItem :: DISPLAY_ICON));
        
        return $toolbar->as_html();
    }
}
?>
