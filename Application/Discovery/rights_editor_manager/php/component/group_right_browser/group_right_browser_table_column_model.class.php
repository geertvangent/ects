<?php
namespace application\discovery\rights_editor_manager;

use common\libraries\ObjectTableColumnModel;
use common\libraries\Utilities;
use common\libraries\ObjectTableColumn;

/**
 * @author Sven Vanpoucke
 * @package application.common.rights_editor_manager.component.location_group_bowser
 */

/**
 * Table column model for the location entity browser
 */
class GroupRightBrowserTableColumnModel extends ObjectTableColumnModel
{
    /**
     * The table right columns
     */
    private static $rights_columns;
    
    /**
     * The parent application component
     */
    private $browser;

    /**
     * Constructor
     */
    function __construct($browser, $columns)
    {
        parent :: __construct($columns);
        
        $this->browser = $browser;
        
        $this->set_default_order_column(1);
        $this->add_rights_columns();
    }

    /**
     * Determines wheter a column is a rights column
     * @param ObjectTableColumn $column
     * @return boolean
     */
    static function is_rights_column($column)
    {
        return in_array($column, self :: $rights_columns);
    }

    /**
     * Adds the rights columns to the column model
     */
    function add_rights_columns()
    {
        $rights = $this->browser->get_available_rights();
        
        foreach ($rights as $right_name => $right_id)
        {
            $column = new ObjectTableColumn($right_name, false);
            $this->add_column($column);
            
            self :: $rights_columns[] = $column;
        }
    }
}
?>