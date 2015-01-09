<?php
namespace Chamilo\Application\Atlantis\Application\Table\Application;

use Chamilo\Libraries\Format\TableColumnModelActionsColumnSupport;
use Chamilo\Libraries\Format\DataClassTableColumnModel;
use Chamilo\Libraries\Format\DataClassPropertyTableColumn;

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
