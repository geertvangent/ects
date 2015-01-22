<?php
namespace Ehb\Application\Atlantis\Context\Table\Context;

use Chamilo\Libraries\Format\Table\Column\DataClassPropertyTableColumn;
use Chamilo\Libraries\Format\Table\Extension\DataClassTable\DataClassTableColumnModel;
use Chamilo\Libraries\Format\Table\Interfaces\TableColumnModelActionsColumnSupport;

class ContextTableColumnModel extends DataClassTableColumnModel implements TableColumnModelActionsColumnSupport
{

    /**
     * Initializes the columns for the table
     */
    public function initialize_columns()
    {
        $this->add_column(
            new DataClassPropertyTableColumn(\Chamilo\Core\Group\Storage\DataClass\Group :: class_name(), \Chamilo\Core\Group\Storage\DataClass\Group :: PROPERTY_NAME));
    }
}
