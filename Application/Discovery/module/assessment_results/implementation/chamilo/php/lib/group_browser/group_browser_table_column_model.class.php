<?php
namespace application\discovery\module\assessment_results\implementation\chamilo;

use libraries\Translation;
use libraries\StaticTableColumn;
use core\group\GroupTableColumnModel;

class GroupBrowserTableColumnModel extends GroupTableColumnModel
{

    /**
     * The tables modification column
     */
    private static $modification_column;

    /**
     * Constructor
     */
    public function initialize_columns()
    {
        parent :: initialize_columns();
        $this->add_column(new StaticTableColumn(Translation :: get('Users', null, 'user')));
        $this->add_column(new StaticTableColumn(Translation :: get('Subgroups')));
    }
}
