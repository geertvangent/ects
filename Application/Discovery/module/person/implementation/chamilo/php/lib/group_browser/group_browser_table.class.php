<?php
namespace application\discovery\module\person\implementation\chamilo;

use common\libraries\Utilities;
use common\libraries\ObjectTable;

class GroupBrowserTable extends ObjectTable
{

    /**
     * Constructor
     *
     * @see ContentObjectTable::ContentObjectTable()
     */
    function __construct($browser, $parameters, $condition)
    {
        $model = new GroupBrowserTableColumnModel();
        $renderer = new GroupBrowserTableCellRenderer($browser);
        $data_provider = new GroupBrowserTableDataProvider($browser, $condition);
        parent :: __construct($data_provider, Utilities :: get_classname_from_namespace(__CLASS__, true), $model,
                $renderer);
        $this->set_additional_parameters($parameters);

        $this->set_default_row_count(20);
    }
}
