<?php
namespace application\atlantis\context;

use libraries\TableColumnModelActionsColumnSupport;
use libraries\NewObjectTableColumnModel;
use libraries\ObjectTableColumn;

class ContextTableColumnModel extends NewObjectTableColumnModel implements TableColumnModelActionsColumnSupport
{

    /**
     * Initializes the columns for the table
     */
    public function initialize_columns()
    {
        $this->add_column(new ObjectTableColumn(\core\group\Group :: PROPERTY_NAME));
    }
}
