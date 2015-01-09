<?php
namespace Chamilo\Application\Atlantis\role\table\role;

use libraries\format\TableColumnModelActionsColumnSupport;
use libraries\format\DataClassTableColumnModel;
use libraries\format\DataClassPropertyTableColumn;

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
