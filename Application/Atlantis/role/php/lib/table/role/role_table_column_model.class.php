<?php
namespace application\atlantis\role;

use libraries\TableColumnModelActionsColumnSupport;
use libraries\DataClassTableColumnModel;
use libraries\DataClassPropertyTableColumn;

class RoleTableColumnModel extends DataClassTableColumnModel implements TableColumnModelActionsColumnSupport
{

    /**
     * Initializes the columns for the table
     */
    public function initialize_columns()
    {
        $this->add_column(new DataClassPropertyTableColumn(Role :: class_name(), Role :: PROPERTY_NAME));
        $this->add_column(new DataClassPropertyTableColumn(Role :: class_name(), Role :: PROPERTY_DESCRIPTION));
    }
}
