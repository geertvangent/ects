<?php
namespace Chamilo\Application\Atlantis\Role\Entitlement\Table\Entitlement;

use Chamilo\Libraries\Platform\Translation\Translation;
use Chamilo\Libraries\Format\StaticTableColumn;
use Chamilo\Libraries\Format\TableColumnModelActionsColumnSupport;
use Chamilo\Libraries\Format\DataClassTableColumnModel;

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
