<?php
namespace Chamilo\Application\Atlantis\Rights\Table\Entity;

use Chamilo\Libraries\Platform\Translation\Translation;
use Chamilo\Libraries\Format\StaticTableColumn;
use Chamilo\Libraries\Format\DataClassTableColumnModel;
use Chamilo\Libraries\Format\TableColumnModelActionsColumnSupport;

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
