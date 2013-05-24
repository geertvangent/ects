<?php
namespace application\discovery\rights_editor_manager;

use common\libraries\ObjectTable;
use common\libraries\Utilities;

/**
 * Table to display the entities for the rights editor
 */
class GroupRightBrowserTable extends ObjectTable
{

    /**
     * Constructor
     * 
     * @see ContentObjectTable::ContentObjectTable()
     */
    public function __construct($browser, $parameters, $condition)
    {
        $selected_entity = $browser->get_selected_entity();
        
        $renderer = new GroupRightBrowserTableCellRenderer($browser);
        
        $model = new GroupRightBrowserTableColumnModel($browser);
        
        $data_provider = new GroupRightBrowserTableDataProvider($browser, $condition);
        
        parent :: __construct(
            $data_provider, 
            Utilities :: get_classname_from_namespace(__CLASS__, true), 
            $model, 
            $renderer);
        
        $this->set_additional_parameters($parameters);
        $this->set_default_row_count(20);
    }
}
