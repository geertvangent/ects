<?php
namespace Chamilo\Application\Atlantis\application\right\table\right;

use libraries\format\TableColumnModelActionsColumnSupport;
use libraries\format\DataClassTableColumnModel;
use libraries\format\DataClassPropertyTableColumn;

class RightTableColumnModel extends DataClassTableColumnModel implements TableColumnModelActionsColumnSupport
{

    /**
     * Initializes the columns for the table
     */
    public function initialize_columns()
    {
        $this->add_column(new DataClassPropertyTableColumn(Right :: class_name(), Right :: PROPERTY_NAME));
        $this->add_column(new DataClassPropertyTableColumn(Right :: class_name(), Right :: PROPERTY_DESCRIPTION));
    }
}
