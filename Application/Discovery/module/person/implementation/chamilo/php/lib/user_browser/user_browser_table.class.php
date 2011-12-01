<?php
namespace application\discovery\module\person\implementation\chamilo;
use common\libraries\Utilities;
use common\libraries\Translation;
use common\libraries\Request;
use common\libraries\ObjectTable;
use common\libraries\ObjectTableFormAction;
use common\libraries\ObjectTableFormActions;
use common\libraries\PlatformSetting;

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