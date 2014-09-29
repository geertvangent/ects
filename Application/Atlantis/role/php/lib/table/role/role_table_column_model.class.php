<?php
namespace application\atlantis\role;

use libraries\TableColumnModelActionsColumnSupport;
use libraries\TableColumnModel;
use libraries\TableColumn;

class RoleTableColumnModel extends TableColumnModel implements TableColumnModelActionsColumnSupport
{

    /**
     * Initializes the columns for the table
     */
    public function initialize_columns()
    {
        $this->add_column(new TableColumn(Role :: PROPERTY_NAME));
        $this->add_column(new TableColumn(Role :: PROPERTY_DESCRIPTION));
    }
}
