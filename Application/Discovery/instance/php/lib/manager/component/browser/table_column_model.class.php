<?php
namespace application\discovery\instance;

use common\libraries\StaticTableColumn;

class InstanceBrowserTableColumnModel extends DefaultInstanceTableColumnModel
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

    public function get_display_order_column_property()
    {
        return Instance :: PROPERTY_DISPLAY_ORDER;
    }

    public static function get_modification_column()
    {
        if (! isset(self :: $modification_column))
        {
            self :: $modification_column = new StaticTableColumn('');
        }
        return self :: $modification_column;
    }
}
