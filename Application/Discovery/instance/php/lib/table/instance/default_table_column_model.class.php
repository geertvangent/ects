<?php
namespace application\discovery\instance;

use libraries\TableColumnModel;
use libraries\TableColumn;

class DefaultInstanceTableColumnModel extends TableColumnModel
{

    public function initialize_columns()
    {
        $this->add_column(new TableColumn(Instance :: PROPERTY_TYPE));
        $this->add_column(new TableColumn(Instance :: PROPERTY_TITLE));
        $this->add_column(new TableColumn(Instance :: PROPERTY_DESCRIPTION));
        $this->add_column(new TableColumn(Instance :: PROPERTY_DISPLAY_ORDER));
    }
}
