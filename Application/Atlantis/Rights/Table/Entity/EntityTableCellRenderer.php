<?php
namespace Chamilo\Application\Atlantis\Rights\Table\Entity;

use Chamilo\Libraries\Format\Structure\ToolbarItem;
use Chamilo\Libraries\Format\Theme\Theme;
use Chamilo\Libraries\Format\Structure\Toolbar;
use Chamilo\Libraries\Format\Table\Interfaces\TableCellRendererActionsColumnSupport;
use Chamilo\Libraries\Format\Table\Extension\DataClassTable\DataClassTableCellRenderer;
use Chamilo\Libraries\Platform\Translation\Translation;
use Chamilo\Libraries\Utilities\Utilities;
use Chamilo\Core\Rights\Entity\UserEntity;
use Chamilo\Core\User\Storage\DataClass\User;
use Chamilo\Core\Group\Storage\DataClass\Group;
use Chamilo\Core\Rights\Entity\PlatformGroupEntity;

class EntityTableCellRenderer extends DataClassTableCellRenderer implements TableCellRendererActionsColumnSupport
{

    public function render_cell($column, $object)
    {
        switch ($column->get_name())
        {
            case Translation :: get('Type') :
                $location_entity_right = $object->get_location_entity_right();

                switch ($location_entity_right->get_entity_type())
                {
                    case UserEntity :: ENTITY_TYPE :
                        $context = User :: context();
                        break;
                    case PlatformGroupEntity :: ENTITY_TYPE :
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
                    case UserEntity :: ENTITY_TYPE :
                        return \Chamilo\Core\User\storage\DataManager :: retrieve(
                            \Chamilo\Core\User\Storage\DataClass\User :: class_name(),
                            (int) $location_entity_right->get_entity_id())->get_fullname();
                    case PlatformGroupEntity :: ENTITY_TYPE :
                        return \Chamilo\Core\Group\storage\DataManager :: retrieve_by_id(
                            \Chamilo\Core\Group\Storage\DataClass\Group :: class_name(),
                            (int) $location_entity_right->get_entity_id())->get_name();
                }
            case Translation :: get('Group') :
                return $object->get_group()->get_name();
            case Translation :: get('Path') :
                return $object->get_group()->get_fully_qualified_name();
        }

        return parent :: render_cell($column, $object);
    }

    public function get_actions($object)
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
