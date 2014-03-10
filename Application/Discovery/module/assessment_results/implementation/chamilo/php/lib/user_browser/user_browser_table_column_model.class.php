<?php
namespace application\discovery\module\assessment_results\implementation\chamilo;

use application\discovery\module\assessment_results\Person;
use common\libraries\ObjectTableColumn;
use common\libraries\NewObjectTableColumnModelActionsColumnSupport;
use common\libraries\NewObjectTableColumnModel;

class UserBrowserTableColumnModel extends NewObjectTableColumnModel implements 
    NewObjectTableColumnModelActionsColumnSupport
{

    public function initialize_columns()
    {
        $user_alias = \user\DataManager :: get_instance()->get_alias(\user\User :: get_table_name());
        $this->add_column(new ObjectTableColumn(\user\User :: PROPERTY_OFFICIAL_CODE, true, $user_alias, true));
        $this->add_column(new ObjectTableColumn(\user\User :: PROPERTY_LASTNAME, true, $user_alias, true));
        $this->add_column(new ObjectTableColumn(\user\User :: PROPERTY_FIRSTNAME, true, $user_alias, true));
        $this->add_column(new ObjectTableColumn(Person :: PROPERTY_EMAIL));
    }
}
