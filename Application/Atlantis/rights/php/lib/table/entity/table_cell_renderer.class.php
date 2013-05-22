<?php
namespace application\atlantis\rights;

use group\GroupDataManager;
use rights\NewPlatformGroupEntity;
use rights\NewUserEntity;
use common\libraries\ToolbarItem;
use common\libraries\Theme;
use common\libraries\Toolbar;
use common\libraries\NewObjectTableCellRendererActionsColumnSupport;
use common\libraries\NewObjectTableCellRenderer;
use common\libraries\Translation;
use common\libraries\Utilities;

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
                        $context = \user\User :: context();
                        break;
                    case NewPlatformGroupEntity :: ENTITY_TYPE :
                        $context = \group\Group :: context();
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
                        return \user\DataManager :: retrieve(
                            \user\User :: class_name(), 
                            (int) $location_entity_right->get_entity_id())->get_fullname();
                    case NewPlatformGroupEntity :: ENTITY_TYPE :
                        return GroupDataManager :: get_instance()->retrieve_group(
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
