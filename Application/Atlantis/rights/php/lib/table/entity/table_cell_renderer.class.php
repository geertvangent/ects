<?php
namespace application\atlantis\rights;

use libraries\ToolbarItem;
use libraries\Theme;
use libraries\Toolbar;
use libraries\NewObjectTableCellRendererActionsColumnSupport;
use libraries\NewObjectTableCellRenderer;
use libraries\Translation;
use libraries\Utilities;
use core\rights\NewUserEntity;
use core\user\User;
use core\group\Group;
use core\rights\NewPlatformGroupEntity;

class EntityTableCellRenderer extends NewObjectTableCellRenderer implements
    NewObjectTableCellRendererActionsColumnSupport
{

    public function render_cell($column, $object)
    {
        switch ($column->get_name())
        {
            case Translation :: get('Type') :
                $location_entity_right = $object->get_location_entity_right();

                switch ($location_entity_right->get_entity_type())
                {
                    case NewUserEntity :: ENTITY_TYPE :
                        $context = User :: context();
                        break;
                    case NewPlatformGroupEntity :: ENTITY_TYPE :
                        $context = Group :: context();
                        break;
                }

                return Theme :: get_image(
                    'logo/16',
                    'png',
                    Translation :: get('TypeName', null, $context),
                    null,
                    ToolbarItem :: DISPLAY_ICON,
                    false,
                    $context);
            case Translation :: get('Entity') :
                $location_entity_right = $object->get_location_entity_right();
                switch ($location_entity_right->get_entity_type())
                {
                    case NewUserEntity :: ENTITY_TYPE :
                        return \core\user\DataManager :: retrieve(
                            \core\user\User :: class_name(),
                            (int) $location_entity_right->get_entity_id())->get_fullname();
                    case NewPlatformGroupEntity :: ENTITY_TYPE :
                        return \core\group\DataManager :: retrieve_by_id(
                            \core\group\Group :: class_name(),
                            (int) $location_entity_right->get_entity_id())->get_name();
                }
            case Translation :: get('Group') :
                return $object->get_group()->get_name();
            case Translation :: get('Path') :
                return $object->get_group()->get_fully_qualified_name();
        }

        return parent :: render_cell($column, $object);
    }

    public function get_object_actions($object)
    {
        $toolbar = new Toolbar();

        if ($this->get_component()->get_user()->is_platform_admin())
        {
            $toolbar->add_item(
                new ToolbarItem(
                    Translation :: get('Delete', null, Utilities :: COMMON_LIBRARIES),
                    Theme :: get_common_image_path() . 'action_delete.png',
                    $this->get_component()->get_url(
                        array(
                            Manager :: PARAM_ACTION => Manager :: ACTION_DELETE,
                            Manager :: PARAM_LOCATION_ENTITY_RIGHT_GROUP_ID => $object->get_id())),
                    ToolbarItem :: DISPLAY_ICON));
        }

        return $toolbar->as_html();
    }
}
