<?php
namespace Chamilo\Application\Atlantis\UserGroup\Table\Application;

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
        $this->add_column(
            new DataClassPropertyTableColumn(
                \Chamilo\Application\Atlantis\Application\Application :: class_name(),
                \Chamilo\Application\Atlantis\Application\Application :: PROPERTY_NAME));
        $this->add_column(
            new DataClassPropertyTableColumn(
                \Chamilo\Application\Atlantis\Application\Application :: class_name(),
                \Chamilo\Application\Atlantis\Application\Application :: PROPERTY_DESCRIPTION));
        $this->add_column(
            new DataClassPropertyTableColumn(
                \Chamilo\Application\Atlantis\Application\Application :: class_name(),
                \Chamilo\Application\Atlantis\Application\Application :: PROPERTY_URL));
    }
}
