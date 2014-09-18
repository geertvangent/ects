<?php
namespace application\atlantis\rights;

use libraries\Translation;
use libraries\StaticTableColumn;
use libraries\NewObjectTableColumnModel;
use libraries\NewObjectTableColumnModelActionsColumnSupport;

class EntityTableColumnModel extends NewObjectTableColumnModel implements NewObjectTableColumnModelActionsColumnSupport
{

    /**
     * Initializes the columns for the table
     */
    public function initialize_columns()
    {
        $this->add_column(new StaticTableColumn(Translation :: get('Type')));
        $this->add_column(new StaticTableColumn(Translation :: get('Entity')));
        $this->add_column(new StaticTableColumn(Translation :: get('Group')));
        $this->add_column(new StaticTableColumn(Translation :: get('Path')));
    }
}
