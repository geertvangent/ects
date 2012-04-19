<?php
namespace application\atlantis\role\entitlement;

use common\libraries\Translation;

use common\libraries\StaticTableColumn;
use common\libraries\NewObjectTableColumnModelActionsColumnSupport;
use common\libraries\NewObjectTableColumnModel;

class EntitlementTableColumnModel extends NewObjectTableColumnModel implements 
        NewObjectTableColumnModelActionsColumnSupport
{

    /**
     * Initializes the columns for the table
     */
    public function initialize_columns()
    {
        $this->add_column(new StaticTableColumn(Translation :: get('TypeName', null, '\application\atlantis\role')));
        $this->add_column(new StaticTableColumn(Translation :: get('TypeName', null, '\application\atlantis\application')));
        $this->add_column(new StaticTableColumn(Translation :: get('TypeName', null, '\application\atlantis\application\right')));
    }
}
?>