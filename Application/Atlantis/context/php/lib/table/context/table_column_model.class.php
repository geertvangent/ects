<?php
namespace application\atlantis\context;

use libraries\TableColumnModelActionsColumnSupport;
use libraries\TableColumnModel;
use libraries\TableColumn;

class ContextTableColumnModel extends TableColumnModel implements TableColumnModelActionsColumnSupport
{

    /**
     * Initializes the columns for the table
     */
    public function initialize_columns()
    {
        $this->add_column(new TableColumn(\core\group\Group :: PROPERTY_NAME));
    }
}
