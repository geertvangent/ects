<?php
namespace application\atlantis\context;

use libraries\format\TableColumnModelActionsColumnSupport;
use libraries\format\DataClassTableColumnModel;
use libraries\format\DataClassPropertyTableColumn;

class ContextTableColumnModel extends DataClassTableColumnModel implements TableColumnModelActionsColumnSupport
{

    /**
     * Initializes the columns for the table
     */
    public function initialize_columns()
    {
        $this->add_column(
            new DataClassPropertyTableColumn(\core\group\Group :: class_name(), \core\group\Group :: PROPERTY_NAME));
    }
}
