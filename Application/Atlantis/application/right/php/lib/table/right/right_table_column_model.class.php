<?php
namespace application\atlantis\application\right;

use libraries\TableColumnModelActionsColumnSupport;
use libraries\TableColumnModel;
use libraries\TableColumn;

class RightTableColumnModel extends TableColumnModel implements TableColumnModelActionsColumnSupport
{

    /**
     * Initializes the columns for the table
     */
    public function initialize_columns()
    {
        $this->add_column(new TableColumn(Right :: PROPERTY_NAME));
        $this->add_column(new TableColumn(Right :: PROPERTY_DESCRIPTION));
    }
}
