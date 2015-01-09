<?php
namespace Chamilo\Application\Atlantis\rights\table\entity;

use libraries\platform\translation\Translation;
use libraries\format\StaticTableColumn;
use libraries\format\DataClassTableColumnModel;
use libraries\format\TableColumnModelActionsColumnSupport;

class EntityTableColumnModel extends DataClassTableColumnModel implements TableColumnModelActionsColumnSupport
{

    /**
     * Initializes the columns for the table
     */
    public function initialize_columns()
    {
        $this->add_column(new StaticTableColumn(Translation :: get('Type')));
        $this->add_column(new StaticTableColumn(Translation :: get('Entity')));
        $this->add_column(new StaticTableColumn(Translation :: get('Group')));
        $this->add_column(new StaticTableColumn(Translation :: get('Path')));
    }
}
