<?php
namespace application\discovery\module\assessment_results\implementation\chamilo;

use core\group\DefaultGroupTableColumnModel;
use libraries\Translation;
use libraries\StaticTableColumn;

class GroupBrowserTableColumnModel extends DefaultGroupTableColumnModel
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
        $this->add_column(new StaticTableColumn(Translation :: get('Users', null, 'user')));
        $this->add_column(new StaticTableColumn(Translation :: get('Subgroups')));
    }
}
