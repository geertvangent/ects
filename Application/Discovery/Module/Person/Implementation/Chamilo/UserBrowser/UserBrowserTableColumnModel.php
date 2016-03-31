<?php
namespace Ehb\Application\Discovery\Module\Person\Implementation\Chamilo\UserBrowser;

use Chamilo\Libraries\Format\Table\Column\TableColumn;
use Chamilo\Libraries\Format\Table\Interfaces\TableColumnModelActionsColumnSupport;
use Chamilo\Libraries\Format\Table\TableColumnModel;
use Ehb\Application\Discovery\Module\Person\Person;

class UserBrowserTableColumnModel extends TableColumnModel implements TableColumnModelActionsColumnSupport
{

    public function initialize_columns()
    {
        $user_alias = \Chamilo\Core\User\Storage\DataManager :: get_instance()->get_alias(
            \Chamilo\Core\User\Storage\DataClass\User :: get_table_name());
        $this->add_column(
            new TableColumn(\Chamilo\Core\User\Storage\DataClass\User :: PROPERTY_OFFICIAL_CODE, true, $user_alias, true));
        $this->add_column(
            new TableColumn(\Chamilo\Core\User\Storage\DataClass\User :: PROPERTY_LASTNAME, true, $user_alias, true));
        $this->add_column(
            new TableColumn(\Chamilo\Core\User\Storage\DataClass\User :: PROPERTY_FIRSTNAME, true, $user_alias, true));
        $this->add_column(new TableColumn(Person :: PROPERTY_EMAIL));
    }
}
