<?php
namespace Chamilo\Application\Atlantis\role\entity\table\role_entity;

use libraries\utilities\DatetimeUtilities;
use libraries\format\table\extension\data_class_table\DataClassTableCellRenderer;
use libraries\format\TableCellRendererActionsColumnSupport;
use libraries\format\structure\Toolbar;
use libraries\format\theme\Theme;
use libraries\platform\translation\Translation;
use libraries\utilities\Utilities;
use libraries\format\structure\ToolbarItem;
use core\rights\PlatformGroupEntity;

class RoleEntityTableCellRenderer extends DataClassTableCellRenderer implements TableCellRendererActionsColumnSupport
{

    public function render_cell($column, $object)
    {
        switch ($column->get_name())
        {
            case RoleEntity :: PROPERTY_ENTITY_TYPE :
                return $object->get_entity_type_image();
                break;
            case Translation :: get('EntityName') :
                return $object->get_entity_name();
                break;
            case Translation :: get('Path') :
                if ($object->get_entity_type() == PlatformGroupEntity :: ENTITY_TYPE)
                {
                    return $object->get_entity()->get_fully_qualified_name();
                }
                break;
            case Translation :: get('Role') :
                return $object->get_role()->get_name();
                break;
            case Translation :: get('Context') :
                return $object->get_context()->get_fully_qualified_name();
                break;
            case RoleEntity :: PROPERTY_START_DATE :
                $date_format = Translation :: get('DateFormatShort', null, Utilities :: COMMON_LIBRARIES);
                return DatetimeUtilities :: format_locale_date($date_format, $object->get_start_date());
                break;
            case RoleEntity :: PROPERTY_END_DATE :
                $date_format = Translation :: get('DateFormatShort', null, Utilities :: COMMON_LIBRARIES);
                return DatetimeUtilities :: format_locale_date($date_format, $object->get_end_date());
                break;
        }

        return parent :: render_cell($column, $object);
    }

    public function get_actions($role_entity)
    {
        $toolbar = new Toolbar();

        $is_target = false;

        if ($this->get_component()->get_user()->is_platform_admin())
        {
            $is_target = true;
        }
        else
        {
            switch ($role_entity->get_entity_type())
            {
                case UserEntity :: ENTITY_TYPE :
                    $is_target = \application\atlantis\rights\Rights :: get_instance()->is_target_user(
                        $this->get_component()->get_user(),
                        $role_entity->get_entity_id());
                    break;
                case PlatformGroupEntity :: ENTITY_TYPE :
                    $is_target = \application\atlantis\rights\Rights :: get_instance()->is_target_group(
                        $this->get_component()->get_user(),
                        $role_entity->get_entity_id());
                    break;
            }
        }

        if ($is_target)
        {
            $toolbar->add_item(
                new ToolbarItem(
                    Translation :: get('Delete', null, Utilities :: COMMON_LIBRARIES),
                    Theme :: get_common_image_path() . 'action_delete.png',
                    $this->get_component()->get_url(
                        array(
                            Manager :: PARAM_ACTION => Manager :: ACTION_DELETE,
                            Manager :: PARAM_ROLE_ENTITY_ID => $role_entity->get_id(),
                            \application\atlantis\role\Manager :: PARAM_ROLE_ID => $this->get_component()->get_role_id(),
                            \application\atlantis\context\Manager :: PARAM_CONTEXT_ID => $this->get_component()->get_context_id())),
                    ToolbarItem :: DISPLAY_ICON));
        }

        return $toolbar->as_html();
    }
}
