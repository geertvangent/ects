<?php
namespace Ehb\Application\Atlantis\Application\Right\Table\Right;

use Chamilo\Libraries\Format\Table\Column\DataClassPropertyTableColumn;
use Chamilo\Libraries\Format\Table\Extension\DataClassTable\DataClassTableColumnModel;
use Chamilo\Libraries\Format\Table\Interfaces\TableColumnModelActionsColumnSupport;
use Ehb\Application\Atlantis\Application\Right\Table\DataClass\Right;

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
