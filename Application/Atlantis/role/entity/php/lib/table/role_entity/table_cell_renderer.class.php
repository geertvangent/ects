<?php
namespace application\atlantis\role\entity;

use common\libraries\DatetimeUtilities;
use common\libraries\NewObjectTableCellRenderer;
use common\libraries\NewObjectTableCellRendererActionsColumnSupport;
use common\libraries\Toolbar;
use common\libraries\Theme;
use common\libraries\Translation;
use common\libraries\Utilities;
use common\libraries\ToolbarItem;

class RoleEntityTableCellRenderer extends NewObjectTableCellRenderer implements 
        NewObjectTableCellRendererActionsColumnSupport
{

    public function render_cell($column, $object)
    {
        switch ($column->get_name())
        {
            case RoleEntity :: PROPERTY_ENTITY_TYPE :
                return $object->get_entity_type_image();
                break;
            case 'entity_name' :
                return $object->get_entity_name();
                break;
            case \application\atlantis\role\Role :: PROPERTY_NAME :
                return $object->get_role()->get_name();
                break;
            case \application\atlantis\context\Context :: PROPERTY_CONTEXT_NAME :
                return $object->get_context()->get_context_name();
                break;
            case RoleEntity :: PROPERTY_START_DATE :
                $date_format = Translation :: get('DateTimeFormatLong', null, Utilities :: COMMON_LIBRARIES);
                return DatetimeUtilities :: format_locale_date($date_format, $object->get_start_date());
                break;
            case RoleEntity :: PROPERTY_END_DATE :
                $date_format = Translation :: get('DateTimeFormatLong', null, Utilities :: COMMON_LIBRARIES);
                return DatetimeUtilities :: format_locale_date($date_format, $object->get_end_date());
                break;
        }
        
        return parent :: render_cell($column, $object);
    }

    function get_object_actions($role_entity)
    {
        $toolbar = new Toolbar();
        if ($this->get_component()->get_user()->is_platform_admin())
        {
            $toolbar->add_item(
                    new ToolbarItem(Translation :: get('Delete', null, Utilities :: COMMON_LIBRARIES), 
                            Theme :: get_common_image_path() . 'action_delete.png', 
                            $this->get_component()->get_url(
                                    array(Manager :: PARAM_ACTION => Manager :: ACTION_DELETE, 
                                            Manager :: PARAM_ROLE_ENTITY_ID => $role_entity->get_id())), 
                            ToolbarItem :: DISPLAY_ICON));
        }
        return $toolbar->as_html();
    }
}
?>
