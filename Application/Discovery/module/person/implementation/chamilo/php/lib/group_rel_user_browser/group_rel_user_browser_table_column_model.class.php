<?php
namespace application\discovery\module\person\implementation\chamilo;

use group\DefaultGroupRelUserTableColumnModel;
use common\libraries\StaticTableColumn;

class GroupRelUserBrowserTableColumnModel extends DefaultGroupRelUserTableColumnModel
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
