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
        if (! $this->get_component()->has_role_id())
        {
            $this->add_column(new StaticTableColumn(Translation :: get('TypeName', null, '\application\atlantis\role')));
        }
        if (! $this->get_component()->has_application_id())
        {
            $this->add_column(new StaticTableColumn(Translation :: get('TypeName', null, '\application\atlantis\application')));
        }
        if (! $this->get_component()->has_right_id())
        {
            $this->add_column(new StaticTableColumn(Translation :: get('TypeName', null, '\application\atlantis\application\right')));
        }
    }
}
