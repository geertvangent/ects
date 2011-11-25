<?php
namespace application\discovery\rights_editor_manager;

use common\libraries\ObjectTableCellRenderer;
use common\libraries\Translation;
use common\libraries\Utilities;
use rights\RightsUtil;

/**
 * @author Sven Vanpoucke
 * @package application.common.rights_editor_manager.component.location_group_bowser
 */

/**
 * Cell renderer for the entity browser table of the rights manager
 */
class GroupRightBrowserTableCellRenderer extends ObjectTableCellRenderer
{
    /**
     * The browser component
     */
    private $browser;

    /**
     * Constructor
     * @param Application $browser
     */
    function __construct($browser)
    {
        $this->browser = $browser;
    }

    // Inherited
    function render_cell($column, $entity_item)
    {
        if (GroupRightBrowserTableColumnModel :: is_rights_column($column))
        {
            return $this->get_rights_column_value($column, $entity_item);
        }
        
        return parent :: render_cell($column, $entity_item);
    }

    function render_id_cell($entity)
    {
        return null;
    }

    /**
     * Determines the value of the rights column
     *
     * @param LocationEntityBrowserTableColumn $column
     * @param Object $entity_item
     * 
     * @return String
     */
    private function get_rights_column_value($column, $entity_item)
    {
        $locations = $this->browser->get_locations();
        $rights = $this->browser->get_available_rights();
        
        $right_id = $rights[$column->get_name()];
        $rights_url = $this->browser->get_url(array(
                RightsEditorManager :: PARAM_ACTION => RightsEditorManager :: ACTION_SET_ENTITY_RIGHTS, 
                RightsEditorManager :: PARAM_ENTITY_ID => $entity_item->get_id(), 
                RightsEditorManager :: PARAM_RIGHT_ID => $right_id));
        
        return $this->get_rights_icon($locations[0], $entity_item->get_id(), $right_id, $rights_url);
    }

    /**
     * Determines the icon of the rights column value
     *
     * @param Location $location
     * @param int $entity_item_id
     * @param int $right_id
     * @param String $rights_url
     *
     * @return String
     */
    private function get_rights_icon($location, $entity_item_id, $right_id, $rights_url)
    {
        $locked_parent = $location->get_locked_parent();
        
        $selected_entity = $this->browser->get_selected_entity();
        $selected_entity_type = $selected_entity->get_entity_type();
        
        $context = $this->browser->get_context();
        
        $rights_util = RightsUtil :: get_instance();
        
        $html = array();
        
        $html[] = '<div id="r|' . $context . '|' . $right_id . '|' . $selected_entity->get_entity_type() . '|' . $entity_item_id . '" style="float: left; width: 24%; text-align: center;">';
        
        if (isset($locked_parent))
        {
            $value = $rights_util->is_allowed_for_rights_entity_item_no_inherit($context, $selected_entity_type, $entity_item_id, $right_id, $locked_parent->get_id());
            
            $html[] = ($value == 1 ? '<img src="' . Theme :: get_common_image_path() . 'action_setting_true_locked.png" title="' . Translation :: get('LockedTrue') . '" />' : '<img src="' . Theme :: get_common_image_path() . 'action_setting_false_locked.png" title="' . Translation :: get('LockedFalse') . '" />');
        }
        else
        {
            $value = $rights_util->is_allowed_for_rights_entity_item_no_inherit($context, $selected_entity_type, $entity_item_id, $right_id, $location->get_id());
            
            if (! $value)
            {
                if ($location->inherits())
                {
                    $inherited_value = $rights_util->is_allowed_for_rights_entity_item($context, $selected_entity_type, $entity_item_id, $right_id, $location);
                    
                    if ($inherited_value)
                    {
                        $html[] = '<a class="setRight" href="' . $rights_url . '">';
                        $html[] = '<div class="rightInheritTrue"></div></a>';
                    }
                    else
                    {
                        $html[] = '<a class="setRight" href="' . $rights_url . '">';
                        $html[] = '<div class="rightFalse"></div></a>';
                    }
                }
                else
                {
                    $html[] = '<a class="setRight" href="' . $rights_url . '">';
                    $html[] = '<div class="rightFalse"></div></a>';
                }
            }
            else
            {
                $html[] = '<a class="setRight" href="' . $rights_url . '">';
                $html[] = '<div class="rightTrue"></div></a>';
            }
        }
        $html[] = '</div>';
        
        return implode("\n", $html);
    }
}
?>