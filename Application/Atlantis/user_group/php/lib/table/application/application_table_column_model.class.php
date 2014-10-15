<?php
namespace application\atlantis\user_group;

use libraries\format\TableColumnModelActionsColumnSupport;
use libraries\format\DataClassTableColumnModel;
use libraries\format\DataClassPropertyTableColumn;

class ApplicationTableColumnModel extends DataClassTableColumnModel implements TableColumnModelActionsColumnSupport
{

    /**
     * Initializes the columns for the table
     */
    public function initialize_columns()
    {
        $this->add_column(
            new DataClassPropertyTableColumn(
                \application\atlantis\application\Application :: class_name(),
                \application\atlantis\application\Application :: PROPERTY_NAME));
        $this->add_column(
            new DataClassPropertyTableColumn(
                \application\atlantis\application\Application :: class_name(),
                \application\atlantis\application\Application :: PROPERTY_DESCRIPTION));
        $this->add_column(
            new DataClassPropertyTableColumn(
                \application\atlantis\application\Application :: class_name(),
                \application\atlantis\application\Application :: PROPERTY_URL));
    }
}
