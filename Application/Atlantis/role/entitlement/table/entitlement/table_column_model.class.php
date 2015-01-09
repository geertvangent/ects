<?php
namespace Chamilo\Application\Atlantis\role\entitlement\table\entitlement;

use libraries\platform\translation\Translation;
use libraries\format\StaticTableColumn;
use libraries\format\TableColumnModelActionsColumnSupport;
use libraries\format\DataClassTableColumnModel;

class EntitlementTableColumnModel extends DataClassTableColumnModel implements TableColumnModelActionsColumnSupport
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
