<?php
namespace application\discovery\module\person\implementation\chamilo;

use core\group\GroupRelUserTableColumnModel;
use libraries\Translation;
use libraries\StaticTableColumn;

class GroupBrowserTableColumnModel extends GroupRelUserTableColumnModel
{

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
