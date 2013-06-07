<?php
namespace application\atlantis\context;

use common\libraries\NewObjectTableColumnModelActionsColumnSupport;
use common\libraries\NewObjectTableColumnModel;
use common\libraries\ObjectTableColumn;

class ContextTableColumnModel extends NewObjectTableColumnModel implements NewObjectTableColumnModelActionsColumnSupport
{

    /**
     * Initializes the columns for the table
     */
    public function initialize_columns()
    {
        $this->add_column(new ObjectTableColumn(\group\Group :: PROPERTY_NAME));
    }
}
