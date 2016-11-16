<?php
namespace Ehb\Application\Atlantis\Role\Entitlement\Table\Entitlement;

use Chamilo\Libraries\Format\Table\Column\StaticTableColumn;
use Chamilo\Libraries\Format\Table\Extension\DataClassTable\DataClassTableColumnModel;
use Chamilo\Libraries\Format\Table\Interfaces\TableColumnModelActionsColumnSupport;
use Chamilo\Libraries\Platform\Translation;

class EntitlementTableColumnModel extends DataClassTableColumnModel implements TableColumnModelActionsColumnSupport
{

    /**
     * Initializes the columns for the table
     */
    public function initialize_columns()
    {
        if (! $this->get_component()->has_role_id())
        {
            $this->add_column(
                new StaticTableColumn(Translation::get('TypeName', null, '\Ehb\Application\Atlantis\Role')));
        }
        if (! $this->get_component()->has_application_id())
        {
            $this->add_column(
                new StaticTableColumn(Translation::get('TypeName', null, '\Ehb\Application\Atlantis\Application')));
        }
        if (! $this->get_component()->has_right_id())
        {
            $this->add_column(
                new StaticTableColumn(Translation::get('TypeName', null, '\Ehb\Application\Atlantis\Application\Right')));
        }
    }
}
