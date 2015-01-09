<?php
namespace Chamilo\Application\Atlantis\Application\Right\Table\Right;

use Chamilo\Libraries\Format\TableColumnModelActionsColumnSupport;
use Chamilo\Libraries\Format\DataClassTableColumnModel;
use Chamilo\Libraries\Format\DataClassPropertyTableColumn;

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
