<?php
namespace application\discovery\rights_editor_manager;

use application\discovery\DiscoveryDataManager;

use application\discovery\PlatformGroupEntity;

use rights\LocationPlatformGroupBrowserTableColumnModel;

use common\libraries\AndCondition;

use application\discovery\RightsGroupEntityRight;

use common\libraries\EqualityCondition;

use common\libraries\Theme;

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
        $rights = $this->browser->get_available_rights();
        
        $right_id = $rights[$column->get_name()];
        $group_id = $this->browser->get_group();
        $module_id = $this->browser->get_parent()->get_module_instance_id();
        
        $conditions[] = new EqualityCondition(RightsGroupEntityRight :: PROPERTY_RIGHT_ID, $right_id);
        $conditions[] = new EqualityCondition(RightsGroupEntityRight :: PROPERTY_GROUP_ID, $group_id);
        $conditions[] = new EqualityCondition(RightsGroupEntityRight :: PROPERTY_MODULE_ID, $module_id);
        $conditions[] = new EqualityCondition(RightsGroupEntityRight :: PROPERTY_ENTITY_ID, $entity_item->get_id());
        $conditions[] = new EqualityCondition(RightsGroupEntityRight :: PROPERTY_ENTITY_TYPE, PlatformGroupEntity :: ENTITY_TYPE);
        
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
?>