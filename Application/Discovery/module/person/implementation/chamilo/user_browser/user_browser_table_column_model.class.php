<?php
namespace Application\Discovery\module\person\implementation\chamilo\user_browser;

use application\discovery\module\person\Person;
use libraries\format\TableColumn;
use libraries\format\TableColumnModelActionsColumnSupport;
use libraries\format\TableColumnModel;

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
