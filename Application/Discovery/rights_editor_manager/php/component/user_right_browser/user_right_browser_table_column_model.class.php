<?php
namespace application\discovery\rights_editor_manager;

use user\UserManager;
use user\User;
use common\libraries\Application;
use common\libraries\ObjectTableColumnModel;
use common\libraries\ObjectTableColumn;

/**
 *
 * @author Sven Vanpoucke
 * @package application.common.rights_editor_manager.component.location_group_bowser
 */

/**
 * Table column model for the location entity browser
 */
class UserRightBrowserTableColumnModel extends ObjectTableColumnModel
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
    public function __construct($browser)
    {
        parent :: __construct($this->get_default_column());
        
        $this->browser = $browser;
        
        $this->set_default_order_column(1);
        $this->add_rights_columns();
    }

    /**
     * Determines wheter a column is a rights column
     * 
     * @param ObjectTableColumn $column
     * @return boolean
     */
    public static function is_rights_column($column)
    {
        return in_array($column, self :: $rights_columns);
    }

    /**
     * Adds the rights columns to the column model
     */
    public function add_rights_columns()
    {
        $rights = $this->browser->get_available_rights();
        
        foreach ($rights as $right_name => $right_id)
        {
            $column = new ObjectTableColumn($right_name, false);
            $this->add_column($column);
            
            self :: $rights_columns[] = $column;
        }
    }

    public function get_default_column()
    {
        $user_namespace = Application :: determine_namespace(UserManager :: APPLICATION_NAME);
        
        $columns = array();
        $columns[] = new ObjectTableColumn(User :: PROPERTY_OFFICIAL_CODE, true, null, true, $user_namespace);
        $columns[] = new ObjectTableColumn(User :: PROPERTY_USERNAME, true, null, true, $user_namespace);
        $columns[] = new ObjectTableColumn(User :: PROPERTY_FIRSTNAME, true, null, true, $user_namespace);
        $columns[] = new ObjectTableColumn(User :: PROPERTY_LASTNAME, true, null, true, $user_namespace);
        
        return $columns;
    }
}
