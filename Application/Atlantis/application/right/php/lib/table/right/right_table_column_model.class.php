<?php
namespace application\atlantis\application\right;

use common\libraries\NewObjectTableColumnModelActionsColumnSupport;

use common\libraries\NewObjectTableColumnModel;

use common\libraries\ObjectTableColumn;

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
?>