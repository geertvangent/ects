<?php
namespace application\atlantis\user_group;

use libraries\TableFormActionsSupport;
use libraries\NewObjectTable;
use libraries\Utilities;
use libraries\Translation;
use libraries\TableFormAction;
use libraries\TableFormActions;

class ApplicationTable extends NewObjectTable implements TableFormActionsSupport
{
    const TABLE_IDENTIFIER = Manager :: PARAM_APPLICATION_ID;
    const DEFAULT_ROW_COUNT = 20;

    public function get_implemented_form_actions()
    {
        $actions = new TableFormActions(__NAMESPACE__, Manager :: PARAM_ACTION);

        $actions->add_form_action(
            new TableFormAction(
                Manager :: ACTION_DELETE,
                Translation :: get('RemoveSelected', null, Utilities :: COMMON_LIBRARIES)));

        return $actions;
    }
}
