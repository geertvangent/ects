<?php
namespace application\discovery;

use common\libraries\Translation;
use common\libraries\ObjectTableColumn;
use common\libraries\StaticTableColumn;

/**
 * $Id: module_instance_browser_table_column_model.class.php 204 2009-11-13 12:51:30Z kariboe $
 * 
 * @package repository.lib.repository_manager.component.browser
 */
/**
 * Table column model for the repository browser table
 */
class ModuleInstanceBrowserTableColumnModel extends DefaultModuleInstanceTableColumnModel
{

    /**
     * The tables modification column
     */
    private static $modification_column;

    /**
     * Constructor
     */
    function __construct()
    {
        parent :: __construct();
        $this->set_default_order_column(1);
        $this->add_column(self :: get_modification_column());
    }

    function get_display_order_column_property()
    {
        return ModuleInstance :: PROPERTY_DISPLAY_ORDER;
    }

    /**
     * Gets the modification column
     * 
     * @return ContentObjectTableColumn
     */
    static function get_modification_column()
    {
        if (! isset(self :: $modification_column))
        {
            self :: $modification_column = new StaticTableColumn('');
        }
        return self :: $modification_column;
    }
}
?>