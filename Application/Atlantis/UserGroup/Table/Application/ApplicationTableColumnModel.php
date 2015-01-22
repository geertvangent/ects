<?php
namespace Ehb\Application\Atlantis\UserGroup\Table\Application;

use Chamilo\Libraries\Format\Table\Column\DataClassPropertyTableColumn;
use Chamilo\Libraries\Format\Table\Extension\DataClassTable\DataClassTableColumnModel;
use Chamilo\Libraries\Format\Table\Interfaces\TableColumnModelActionsColumnSupport;

class ApplicationTableColumnModel extends DataClassTableColumnModel implements TableColumnModelActionsColumnSupport
{

    /**
     * Initializes the columns for the table
     */
    public function initialize_columns()
    {
        $this->add_column(
            new DataClassPropertyTableColumn(
                \Ehb\Application\Atlantis\Application\Storage\DataClass\Application :: class_name(),
                \Ehb\Application\Atlantis\Application\Storage\DataClass\Application :: PROPERTY_NAME));
        $this->add_column(
            new DataClassPropertyTableColumn(
                \Ehb\Application\Atlantis\Application\Storage\DataClass\Application :: class_name(),
                \Ehb\Application\Atlantis\Application\Storage\DataClass\Application :: PROPERTY_DESCRIPTION));
        $this->add_column(
            new DataClassPropertyTableColumn(
                \Ehb\Application\Atlantis\Application\Storage\DataClass\Application :: class_name(),
                \Ehb\Application\Atlantis\Application\Storage\DataClass\Application :: PROPERTY_URL));
    }
}
