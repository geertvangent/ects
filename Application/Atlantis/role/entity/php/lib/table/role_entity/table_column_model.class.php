<?php
namespace application\atlantis\role\entity;

use libraries\Translation;
use libraries\StaticTableColumn;
use libraries\NewObjectTableColumnModelActionsColumnSupport;
use libraries\NewObjectTableColumnModel;
use libraries\ObjectTableColumn;

class RoleEntityTableColumnModel extends NewObjectTableColumnModel implements
    NewObjectTableColumnModelActionsColumnSupport
{

    /**
     * Initializes the columns for the table
     */
    public function initialize_columns()
    {
        if (! $this->get_component()->has_entity())
        {
            $this->add_column(new ObjectTableColumn(RoleEntity :: PROPERTY_ENTITY_TYPE, false));
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

        $this->add_column(new ObjectTableColumn(RoleEntity :: PROPERTY_START_DATE, false));
        $this->add_column(new ObjectTableColumn(RoleEntity :: PROPERTY_END_DATE, false));
    }
}
