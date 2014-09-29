<?php
namespace application\atlantis\role\entity;

use libraries\Translation;
use libraries\StaticTableColumn;
use libraries\TableColumnModelActionsColumnSupport;
use libraries\TableColumnModel;
use libraries\TableColumn;

class RoleEntityTableColumnModel extends TableColumnModel implements TableColumnModelActionsColumnSupport
{

    /**
     * Initializes the columns for the table
     */
    public function initialize_columns()
    {
        if (! $this->get_component()->has_entity())
        {
            $this->add_column(new TableColumn(RoleEntity :: PROPERTY_ENTITY_TYPE, false));
            $this->add_column(new StaticTableColumn(Translation :: get('EntityName')));
            $this->add_column(new StaticTableColumn(Translation :: get('Path')));
        }
        if (! $this->get_component()->has_role_id())
        {
            $this->add_column(new StaticTableColumn(Translation :: get('Role')));
        }
        if (! $this->get_component()->has_context_id())
        {
            $this->add_column(new StaticTableColumn(Translation :: get('Context')));
        }

        $this->add_column(new TableColumn(RoleEntity :: PROPERTY_START_DATE, false));
        $this->add_column(new TableColumn(RoleEntity :: PROPERTY_END_DATE, false));
    }
}
