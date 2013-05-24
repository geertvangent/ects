<?php
namespace application\discovery;

use common\libraries\Utilities;
use common\libraries\ObjectTable;

/**
 * $Id: user_browser_table.class.php 211 2009-11-13 13:28:39Z vanpouckesven $
 * 
 * @package user.lib.user_manager.component.user_browser
 */
/**
 * Table to display a set of users.
 */
class UserBrowserTable extends ObjectTable
{

    /**
     * Constructor
     * 
     * @see ContentObjectTable::ContentObjectTable()
     */
    public function __construct($browser, $parameters, $condition)
    {
        $model = new UserBrowserTableColumnModel();
        $renderer = new UserBrowserTableCellRenderer($browser);
        $data_provider = new UserBrowserTableDataProvider($browser, $condition);
        parent :: __construct(
            $data_provider, 
            Utilities :: get_classname_from_namespace(__CLASS__, true), 
            $model, 
            $renderer);
        $this->set_additional_parameters($parameters);
        
        $this->set_default_row_count(20);
    }
}
