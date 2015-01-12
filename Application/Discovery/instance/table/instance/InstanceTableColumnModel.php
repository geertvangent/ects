<?php
namespace Application\Discovery\instance\table\instance;

use libraries\format\TableColumnModelActionsColumnSupport;
use libraries\format\DataClassPropertyTableColumn;
use libraries\format\DataClassTableColumnModel;
use libraries\format\DisplayOrderPropertyTableColumn;

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
