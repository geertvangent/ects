<?php
namespace application\atlantis\role;

use libraries\NewObjectTableColumnModelActionsColumnSupport;
use libraries\NewObjectTableColumnModel;
use libraries\ObjectTableColumn;

class RoleTableColumnModel extends NewObjectTableColumnModel implements NewObjectTableColumnModelActionsColumnSupport
{

    /**
     * Initializes the columns for the table
     */
    public function initialize_columns()
    {
        $this->add_column(new ObjectTableColumn(Role :: PROPERTY_NAME));
        $this->add_column(new ObjectTableColumn(Role :: PROPERTY_DESCRIPTION));
    }
}
