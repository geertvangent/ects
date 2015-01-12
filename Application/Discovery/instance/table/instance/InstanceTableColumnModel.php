<?php
namespace Chamilo\Application\Discovery\Instance\Table\Instance;

use Chamilo\Libraries\Format\TableColumnModelActionsColumnSupport;
use Chamilo\Libraries\Format\DataClassPropertyTableColumn;
use Chamilo\Libraries\Format\DataClassTableColumnModel;
use Chamilo\Libraries\Format\DisplayOrderPropertyTableColumn;

class InstanceTableColumnModel extends DataClassTableColumnModel implements TableColumnModelActionsColumnSupport
{

    public function initialize_columns()
    {
        $this->add_column(new DataClassPropertyTableColumn(Instance :: class_name(), Instance :: PROPERTY_TYPE));
        $this->add_column(new DataClassPropertyTableColumn(Instance :: class_name(), Instance :: PROPERTY_TITLE));
        $this->add_column(new DataClassPropertyTableColumn(Instance :: class_name(), Instance :: PROPERTY_DESCRIPTION));
        $this->add_column(
            new DisplayOrderPropertyTableColumn(Instance :: class_name(), Instance :: PROPERTY_DISPLAY_ORDER));
    }
}
