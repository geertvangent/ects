<?php
namespace application\discovery\instance;

use common\libraries\ObjectTable;

class InstanceBrowserTable extends ObjectTable
{
    const DEFAULT_NAME = 'instance_browser_table';

    public function __construct($browser, $parameters, $condition)
    {
        $model = new InstanceBrowserTableColumnModel();
        $renderer = new InstanceBrowserTableCellRenderer($browser);
        $data_provider = new InstanceBrowserTableDataProvider($browser, $condition);
        parent :: __construct($data_provider, self :: DEFAULT_NAME, $model, $renderer);

        $this->set_additional_parameters($parameters);
        $this->set_default_row_count(20);
    }
}
