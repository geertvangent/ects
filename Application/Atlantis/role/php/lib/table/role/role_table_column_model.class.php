<?php
namespace application\atlantis\role;

use common\libraries\NewObjectTableColumnModelActionsColumnSupport;

use common\libraries\NewObjectTableColumnModel;

use common\libraries\ObjectTableColumn;

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
?>