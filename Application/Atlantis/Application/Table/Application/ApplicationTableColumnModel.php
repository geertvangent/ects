<?php
namespace Chamilo\Application\Atlantis\Application\Table\Application;

use Chamilo\Libraries\Format\Table\Interfaces\TableColumnModelActionsColumnSupport;
use Chamilo\Libraries\Format\Table\Extension\DataClassTable\DataClassTableColumnModel;
use Chamilo\Libraries\Format\Table\Column\DataClassPropertyTableColumn;
use Chamilo\Application\Atlantis\Application\Storage\DataClass\Application;

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
