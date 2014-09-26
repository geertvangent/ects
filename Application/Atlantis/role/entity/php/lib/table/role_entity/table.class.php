<?php
namespace application\atlantis\role\entity;

use libraries\TableFormActionsSupport;
use libraries\NewObjectTable;
use libraries\Utilities;
use libraries\Translation;
use libraries\TableFormAction;
use libraries\TableFormActions;

class RoleEntityTable extends NewObjectTable implements TableFormActionsSupport
{
    const TABLE_IDENTIFIER = Manager :: PARAM_ROLE_ENTITY_ID;
    const DEFAULT_ROW_COUNT = 20;

    public function get_implemented_form_actions()
    {
        $actions = new TableFormActions(__NAMESPACE__, Manager :: PARAM_ACTION);
        if ($this->get_component()->get_user()->is_platform_admin())
        {
            $actions->add_form_action(
                new TableFormAction(
                    Manager :: ACTION_DELETE,
                    Translation :: get('RemoveSelected', null, Utilities :: COMMON_LIBRARIES)));
        }
        return $actions;
    }
}
