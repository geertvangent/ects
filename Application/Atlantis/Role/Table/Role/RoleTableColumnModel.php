<?php
namespace Chamilo\Application\Atlantis\Role\Table\Role;

use Chamilo\Libraries\Format\TableColumnModelActionsColumnSupport;
use Chamilo\Libraries\Format\DataClassTableColumnModel;
use Chamilo\Libraries\Format\DataClassPropertyTableColumn;

class RoleTableColumnModel extends DataClassTableColumnModel implements TableColumnModelActionsColumnSupport
{

    /**
     * Initializes the columns for the table
     */
    public function initialize_columns()
    {
        $this->add_column(new DataClassPropertyTableColumn(Role :: class_name(), Role :: PROPERTY_NAME));
        $this->add_column(new DataClassPropertyTableColumn(Role :: class_name(), Role :: PROPERTY_DESCRIPTION));
    }
}
