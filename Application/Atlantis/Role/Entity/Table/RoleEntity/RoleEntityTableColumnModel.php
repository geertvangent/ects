<?php
namespace Chamilo\Application\Atlantis\Role\Entity\Table\RoleEntity;

use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Format\Table\Column\StaticTableColumn;
use Chamilo\Libraries\Format\Table\Interfaces\TableColumnModelActionsColumnSupport;
use Chamilo\Libraries\Format\Table\Column\DataClassPropertyTableColumn;
use Chamilo\Libraries\Format\Table\Extension\DataClassTable\DataClassTableColumnModel;
use Chamilo\Application\Atlantis\Role\Entity\Storage\DataClass\RoleEntity;

class RoleEntityTableColumnModel extends DataClassTableColumnModel implements TableColumnModelActionsColumnSupport
{

    /**
     * Initializes the columns for the table
     */
    public function initialize_columns()
    {
        if (! $this->get_component()->has_entity())
        {
            $this->add_column(
                new DataClassPropertyTableColumn(RoleEntity :: class_name(), RoleEntity :: PROPERTY_ENTITY_TYPE, false));
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

        $this->add_column(
            new DataClassPropertyTableColumn(RoleEntity :: class_name(), RoleEntity :: PROPERTY_START_DATE, false));
        $this->add_column(
            new DataClassPropertyTableColumn(RoleEntity :: class_name(), RoleEntity :: PROPERTY_END_DATE, false));
    }
}
