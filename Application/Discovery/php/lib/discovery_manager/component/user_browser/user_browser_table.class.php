<?php
namespace application\discovery;

use common\libraries\Utilities;
use common\libraries\Translation;
use common\libraries\Request;
use common\libraries\ObjectTable;
use common\libraries\ObjectTableFormAction;
use common\libraries\ObjectTableFormActions;
use common\libraries\PlatformSetting;

/**
 * $Id: user_browser_table.class.php 211 2009-11-13 13:28:39Z vanpouckesven $
 * @package user.lib.user_manager.component.user_browser
 */
require_once dirname(__FILE__) . '/user_browser_table_data_provider.class.php';
require_once dirname(__FILE__) . '/user_browser_table_column_model.class.php';
require_once dirname(__FILE__) . '/user_browser_table_cell_renderer.class.php';
/**
 * Table to display a set of users.
 */
class UserBrowserTable extends ObjectTable
{

    /**
     * Constructor
     * @see ContentObjectTable::ContentObjectTable()
     */
    function __construct($browser, $parameters, $condition)
    {
        $model = new UserBrowserTableColumnModel();
        $renderer = new UserBrowserTableCellRenderer($browser);
        $data_provider = new UserBrowserTableDataProvider($browser, $condition);
        parent :: __construct($data_provider, Utilities :: get_classname_from_namespace(__CLASS__, true), $model, $renderer);
        $this->set_additional_parameters($parameters);
        
        $this->set_default_row_count(20);
    }
}
?>