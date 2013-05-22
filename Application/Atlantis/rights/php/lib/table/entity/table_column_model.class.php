<?php
namespace application\atlantis\rights;

use common\libraries\Translation;
use common\libraries\StaticTableColumn;
use common\libraries\NewObjectTableColumnModel;
use common\libraries\NewObjectTableColumnModelActionsColumnSupport;

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
