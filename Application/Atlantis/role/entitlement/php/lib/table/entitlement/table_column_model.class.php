<?php
namespace application\atlantis\role\entitlement;

use libraries\Translation;
use libraries\StaticTableColumn;
use libraries\TableColumnModelActionsColumnSupport;
use libraries\TableColumnModel;

class EntitlementTableColumnModel extends TableColumnModel implements TableColumnModelActionsColumnSupport
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
            $this->add_column(
                new StaticTableColumn(Translation :: get('TypeName', null, '\application\atlantis\application')));
        }
        if (! $this->get_component()->has_right_id())
        {
            $this->add_column(
                new StaticTableColumn(Translation :: get('TypeName', null, '\application\atlantis\application\right')));
        }
    }
}
