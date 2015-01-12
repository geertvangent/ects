<?php
namespace Chamilo\Application\Discovery\Module\Person\Implementation\Chamilo\UserBrowser;

use Chamilo\Application\Discovery\Module\Person\Person;
use Chamilo\Libraries\Format\TableColumn;
use Chamilo\Libraries\Format\TableColumnModelActionsColumnSupport;
use Chamilo\Libraries\Format\TableColumnModel;

class UserBrowserTableColumnModel extends TableColumnModel implements TableColumnModelActionsColumnSupport
{

    public function initialize_columns()
    {
        $user_alias = \Chamilo\Core\User\DataManager :: get_instance()->get_alias(\Chamilo\Core\User\User :: get_table_name());
        $this->add_column(new TableColumn(\Chamilo\Core\User\User :: PROPERTY_OFFICIAL_CODE, true, $user_alias, true));
        $this->add_column(new TableColumn(\Chamilo\Core\User\User :: PROPERTY_LASTNAME, true, $user_alias, true));
        $this->add_column(new TableColumn(\Chamilo\Core\User\User :: PROPERTY_FIRSTNAME, true, $user_alias, true));
        $this->add_column(new TableColumn(Person :: PROPERTY_EMAIL));
    }
}
