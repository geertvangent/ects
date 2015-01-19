<?php
namespace Ehb\Application\Atlantis\Role\Table\Role;

use Chamilo\Libraries\Format\Table\Interfaces\TableColumnModelActionsColumnSupport;
use Chamilo\Libraries\Format\Table\Extension\DataClassTable\DataClassTableColumnModel;
use Chamilo\Libraries\Format\Table\Column\DataClassPropertyTableColumn;
use Ehb\Application\Atlantis\Role\DataClass\Role;

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
