<?php
namespace application\atlantis\application;

use libraries\TableColumnModelActionsColumnSupport;
use libraries\DataClassTableColumnModel;
use libraries\DataClassPropertyTableColumn;

class ApplicationTableColumnModel extends DataClassTableColumnModel implements TableColumnModelActionsColumnSupport
{

    /**
     * Initializes the columns for the table
     */
    public function initialize_columns()
    {
        $this->add_column(new DataClassPropertyTableColumn(Application :: class_name(), Application :: PROPERTY_NAME));
        $this->add_column(
            new DataClassPropertyTableColumn(Application :: class_name(), Application :: PROPERTY_DESCRIPTION));
        $this->add_column(new DataClassPropertyTableColumn(Application :: class_name(), Application :: PROPERTY_URL));
    }
}
