<?php
namespace Ehb\Application\Calendar\Extension\SyllabusPlus\Table\User;

use Chamilo\Libraries\Format\Table\Extension\DataClassTable\DataClassTable;
use Chamilo\Libraries\Format\Table\FormAction\TableFormActions;
use Chamilo\Libraries\Format\Table\Interfaces\TableFormActionsSupport;

/**
 * Table to display a set of users.
 */
class UserTable extends DataClassTable implements TableFormActionsSupport
{
    const TABLE_IDENTIFIER = \Ehb\Application\Calendar\Extension\SyllabusPlus\Manager::PARAM_USER_USER_ID;

    public function get_implemented_form_actions()
    {
        $actions = new TableFormActions(__NAMESPACE__, self::TABLE_IDENTIFIER);
        
        return $actions;
    }
}
