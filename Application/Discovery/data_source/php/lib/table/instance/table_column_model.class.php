<?php
namespace application\discovery\data_source;

use libraries\TableColumnModelActionsColumnSupport;
use libraries\DataClassPropertyTableColumn;
use libraries\DataClassTableColumnModel;

class InstanceTableColumnModel extends DataClassTableColumnModel implements TableColumnModelActionsColumnSupport
{

    public function initialize_columns()
    {
        $this->add_column(new DataClassPropertyTableColumn(Instance :: class_name(), Instance :: PROPERTY_TYPE));
        $this->add_column(new DataClassPropertyTableColumn(Instance :: class_name(), Instance :: PROPERTY_NAME));
        $this->add_column(new DataClassPropertyTableColumn(Instance :: class_name(), Instance :: PROPERTY_DESCRIPTION));
    }
}
