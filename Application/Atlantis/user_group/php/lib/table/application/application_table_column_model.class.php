<?php
namespace application\atlantis\user_group;

use common\libraries\NewObjectTableColumnModelActionsColumnSupport;
use common\libraries\NewObjectTableColumnModel;
use common\libraries\ObjectTableColumn;

class ApplicationTableColumnModel extends NewObjectTableColumnModel implements
        NewObjectTableColumnModelActionsColumnSupport
{

    /**
     * Initializes the columns for the table
     */
    public function initialize_columns()
    {
        $this->add_column(new ObjectTableColumn(Application :: PROPERTY_NAME));
        $this->add_column(new ObjectTableColumn(Application :: PROPERTY_DESCRIPTION));
        $this->add_column(new ObjectTableColumn(Application :: PROPERTY_URL));
    }
}
?>