<?php
namespace Application\Discovery\data_source\table\instance;

use libraries\format\TableColumnModelActionsColumnSupport;
use libraries\format\DataClassPropertyTableColumn;
use libraries\format\DataClassTableColumnModel;

class InstanceTableColumnModel extends DataClassTableColumnModel implements TableColumnModelActionsColumnSupport
{

    public function initialize_columns()
    {
        $this->add_column(new DataClassPropertyTableColumn(Instance :: class_name(), Instance :: PROPERTY_TYPE));
        $this->add_column(new DataClassPropertyTableColumn(Instance :: class_name(), Instance :: PROPERTY_NAME));
        $this->add_column(new DataClassPropertyTableColumn(Instance :: class_name(), Instance :: PROPERTY_DESCRIPTION));
    }
}
