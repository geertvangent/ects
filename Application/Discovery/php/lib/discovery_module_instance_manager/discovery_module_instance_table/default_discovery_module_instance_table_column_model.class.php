<?php
namespace application\discovery;

use common\libraries\Path;
use common\libraries\ObjectTableColumnModel;
use common\libraries\ObjectTableColumn;

require_once Path :: get_library_path() . 'html/table/object_table/object_table_column_model.class.php';

class DefaultModuleInstanceTableColumnModel extends ObjectTableColumnModel
{

    /**
     * Constructor
     */
    function __construct()
    {
        parent :: __construct(self :: get_default_columns(), 1);
    }

    /**
     * Gets the default columns for this model
     * @return ContentObjectTableColumn[]
     */
    private static function get_default_columns()
    {
        $columns = array();
        $columns[] = new ObjectTableColumn(ModuleInstance :: PROPERTY_TYPE);
        $columns[] = new ObjectTableColumn(ModuleInstance :: PROPERTY_TITLE);
        $columns[] = new ObjectTableColumn(ModuleInstance :: PROPERTY_DESCRIPTION);
        return $columns;
    }
}
?>