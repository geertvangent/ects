<?php
namespace application\atlantis\user_group;

use libraries\NewObjectTableColumnModelActionsColumnSupport;
use libraries\NewObjectTableColumnModel;
use libraries\ObjectTableColumn;

class ApplicationTableColumnModel extends NewObjectTableColumnModel implements 
    NewObjectTableColumnModelActionsColumnSupport
{

    /**
     * Initializes the columns for the table
     */
    public function initialize_columns()
    {
        $this->add_column(new ObjectTableColumn(\application\atlantis\application\Application :: PROPERTY_NAME));
        $this->add_column(new ObjectTableColumn(\application\atlantis\application\Application :: PROPERTY_DESCRIPTION));
        $this->add_column(new ObjectTableColumn(\application\atlantis\application\Application :: PROPERTY_URL));
    }
}
