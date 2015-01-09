<?php
namespace Chamilo\Application\Atlantis\Context\Table\Context;

use Chamilo\Libraries\Format\TableColumnModelActionsColumnSupport;
use Chamilo\Libraries\Format\DataClassTableColumnModel;
use Chamilo\Libraries\Format\DataClassPropertyTableColumn;

class ContextTableColumnModel extends DataClassTableColumnModel implements TableColumnModelActionsColumnSupport
{

    /**
     * Initializes the columns for the table
     */
    public function initialize_columns()
    {
        $this->add_column(
            new DataClassPropertyTableColumn(\Chamilo\Core\Group\Group :: class_name(), \Chamilo\Core\Group\Group :: PROPERTY_NAME));
    }
}
