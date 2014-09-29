<?php
namespace application\discovery\data_source;

use libraries\TableColumnModel;
use libraries\TableColumn;

class DefaultInstanceTableColumnModel extends TableColumnModel
{

    public function initialize_columns()
    {
        $this->add_column(new TableColumn(Instance :: PROPERTY_TYPE));
        $this->add_column(new TableColumn(Instance :: PROPERTY_NAME));
        $this->add_column(new TableColumn(Instance :: PROPERTY_DESCRIPTION));
    }
}
