<?php
namespace application\discovery\module\person\implementation\chamilo;

use application\discovery\module\person\Person;
use libraries\TableColumn;
use libraries\TableColumnModelActionsColumnSupport;
use libraries\TableColumnModel;

class UserBrowserTableColumnModel extends TableColumnModel implements TableColumnModelActionsColumnSupport
{

    public function initialize_columns()
    {
        $user_alias = \core\user\DataManager :: get_instance()->get_alias(\core\user\User :: get_table_name());
        $this->add_column(new TableColumn(\core\user\User :: PROPERTY_OFFICIAL_CODE, true, $user_alias, true));
        $this->add_column(new TableColumn(\core\user\User :: PROPERTY_LASTNAME, true, $user_alias, true));
        $this->add_column(new TableColumn(\core\user\User :: PROPERTY_FIRSTNAME, true, $user_alias, true));
        $this->add_column(new TableColumn(Person :: PROPERTY_EMAIL));
    }
}
