<?php
namespace application\discovery;

use common\libraries\Request;
use common\libraries\Translation;
use common\libraries\Utilities;
use common\libraries\ObjectTable;
use common\libraries\ObjectTableFormActions;
use common\libraries\ObjectTableFormAction;
/**
 * $Id: discovery_module_instance_browser_table.class.php 204 2009-11-13 12:51:30Z kariboe $
 * @package repository.lib.repository_manager.component.browser
 */
require_once dirname(__FILE__) . '/discovery_module_instance_browser_table_data_provider.class.php';
require_once dirname(__FILE__) . '/discovery_module_instance_browser_table_column_model.class.php';
require_once dirname(__FILE__) . '/discovery_module_instance_browser_table_cell_renderer.class.php';
/**
 * Table to display a set of learning objects.
 */
class DiscoveryModuleInstanceBrowserTable extends ObjectTable
{
    const DEFAULT_NAME = 'discovery_module_instance_browser_table';

    /**
     * Constructor
     * @see ContentObjectTable::ContentObjectTable()
     */
    function __construct($browser, $parameters, $condition)
    {
        $model = new DiscoveryModuleInstanceBrowserTableColumnModel();
        $renderer = new DiscoveryModuleInstanceBrowserTableCellRenderer($browser);
        $data_provider = new DiscoveryModuleInstanceBrowserTableDataProvider($browser, $condition);
        parent :: __construct($data_provider, self :: DEFAULT_NAME, $model, $renderer);

        $this->set_additional_parameters($parameters);
        $this->set_default_row_count(20);
    }
}
?>