<?php
namespace application\discovery\rights_editor_manager;

use application\discovery\UserEntity;
use application\discovery\DiscoveryDataManager;
use common\libraries\AndCondition;
use application\discovery\RightsGroupEntityRight;
use common\libraries\EqualityCondition;
use common\libraries\ObjectTableCellRenderer;

/**
 *
 * @author Sven Vanpoucke
 * @package application.common.rights_editor_manager.component.location_group_bowser
 */

/**
 * Cell renderer for the entity browser table of the rights manager
 */
class UserRightBrowserTableCellRenderer extends ObjectTableCellRenderer
{

    /**
     * The browser component
     */
    private $browser;

    /**
     * Constructor
     * 
     * @param Application $browser
     */
    public function __construct($browser)
    {
        $this->browser = $browser;
    }
    
    // Inherited
    public function render_cell($column, $entity_item)
    {
        if (UserRightBrowserTableColumnModel :: is_rights_column($column))
        {
            return $this->get_rights_column_value($column, $entity_item);
        }
        
        return parent :: render_cell($column, $entity_item);
    }

    public function render_id_cell($entity)
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
        $rights = $this->browser->get_available_rights();
        
        $right_id = $rights[$column->get_name()];
        $group_id = $this->browser->get_group();
        $module_id = $this->browser->get_parent()->get_module_instance_id();
        
        $conditions[] = new EqualityCondition(RightsGroupEntityRight :: PROPERTY_RIGHT_ID, $right_id);
        $conditions[] = new EqualityCondition(RightsGroupEntityRight :: PROPERTY_GROUP_ID, $group_id);
        $conditions[] = new EqualityCondition(RightsGroupEntityRight :: PROPERTY_MODULE_ID, $module_id);
        $conditions[] = new EqualityCondition(RightsGroupEntityRight :: PROPERTY_ENTITY_ID, $entity_item->get_id());
        $conditions[] = new EqualityCondition(RightsGroupEntityRight :: PROPERTY_ENTITY_TYPE, UserEntity :: ENTITY_TYPE);
        
        $condition = new AndCondition($conditions);
        
        $count = DiscoveryDataManager :: get_instance()->count_rights_group_entity_rights($condition);
        
        if ($count >= 1)
        {
            return '<div class="rightTrue"></div>';
        }
        else
        {
            return '<div class="rightFalse"></div>';
        }
    }
}
