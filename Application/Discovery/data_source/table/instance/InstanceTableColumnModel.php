<?php
namespace Chamilo\Application\Discovery\DataSource\Table\Instance;

use Chamilo\Libraries\Format\TableColumnModelActionsColumnSupport;
use Chamilo\Libraries\Format\DataClassPropertyTableColumn;
use Chamilo\Libraries\Format\DataClassTableColumnModel;

class InstanceTableColumnModel extends DataClassTableColumnModel implements TableColumnModelActionsColumnSupport
{

    public function initialize_columns()
    {
        $this->add_column(new DataClassPropertyTableColumn(Instance :: class_name(), Instance :: PROPERTY_TYPE));
        $this->add_column(new DataClassPropertyTableColumn(Instance :: class_name(), Instance :: PROPERTY_NAME));
        $this->add_column(new DataClassPropertyTableColumn(Instance :: class_name(), Instance :: PROPERTY_DESCRIPTION));
    }
}
