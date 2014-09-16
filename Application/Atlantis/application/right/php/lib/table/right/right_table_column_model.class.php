<?php
namespace application\atlantis\application\right;

use libraries\NewObjectTableColumnModelActionsColumnSupport;
use libraries\NewObjectTableColumnModel;
use libraries\ObjectTableColumn;

class RightTableColumnModel extends NewObjectTableColumnModel implements NewObjectTableColumnModelActionsColumnSupport
{

    /**
     * Initializes the columns for the table
     */
    public function initialize_columns()
    {
        $this->add_column(new ObjectTableColumn(Right :: PROPERTY_NAME));
        $this->add_column(new ObjectTableColumn(Right :: PROPERTY_DESCRIPTION));
    }
}
