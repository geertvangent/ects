<?php
namespace Ehb\Application\Discovery\Module\Person\Implementation\Chamilo\GroupBrowser;

use Chamilo\Libraries\Format\Table\Table;
use Chamilo\Libraries\Utilities\Utilities;

class GroupBrowserTable extends Table
{

    /**
     * Constructor
     * 
     * @see ContentObjectTable::ContentObjectTable()
     */
    public function __construct($browser, $parameters, $condition)
    {
        $model = new GroupBrowserTableColumnModel();
        $renderer = new GroupBrowserTableCellRenderer($browser);
        $data_provider = new GroupBrowserTableDataProvider($browser, $condition);
        parent::__construct($data_provider, Utilities::get_classname_from_namespace(__CLASS__, true), $model, $renderer);
        $this->setAdditionalParameters($parameters);
        
        $this->set_default_row_count(20);
    }
}
