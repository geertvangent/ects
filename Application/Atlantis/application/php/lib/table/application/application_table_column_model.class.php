<?php
namespace application\atlantis\application;

use libraries\TableColumnModelActionsColumnSupport;
use libraries\TableColumnModel;
use libraries\TableColumn;

class ApplicationTableColumnModel extends TableColumnModel implements TableColumnModelActionsColumnSupport
{

    /**
     * Initializes the columns for the table
     */
    public function initialize_columns()
    {
        $this->add_column(new TableColumn(Application :: PROPERTY_NAME));
        $this->add_column(new TableColumn(Application :: PROPERTY_DESCRIPTION));
        $this->add_column(new TableColumn(Application :: PROPERTY_URL));
    }
}
