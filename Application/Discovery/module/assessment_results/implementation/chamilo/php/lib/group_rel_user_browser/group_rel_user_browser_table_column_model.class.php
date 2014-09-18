<?php
namespace application\discovery\module\assessment_results\implementation\chamilo;

use libraries\StaticTableColumn;
use core\group\DefaultGroupRelUserTableColumnModel;

class GroupRelUserBrowserTableColumnModel extends DefaultGroupRelUserTableColumnModel
{

    /**
     * The tables modification column
     */
    private static $modification_column;

    /**
     * Constructor
     */
    public function __construct()
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
    public static function get_modification_column()
    {
        if (! isset(self :: $modification_column))
        {
            self :: $modification_column = new StaticTableColumn('');
        }
        return self :: $modification_column;
    }
}
