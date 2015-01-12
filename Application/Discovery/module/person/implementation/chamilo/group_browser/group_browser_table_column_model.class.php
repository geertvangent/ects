<?php
namespace Application\Discovery\module\person\implementation\chamilo\group_browser;

use core\group\GroupRelUserTableColumnModel;
use libraries\platform\translation\Translation;
use libraries\format\StaticTableColumn;

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
