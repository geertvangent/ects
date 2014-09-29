<?php
namespace application\atlantis\rights;

use libraries\Translation;
use libraries\TableFormAction;
use libraries\TableFormActions;
use libraries\TableFormActionsSupport;
use libraries\TableColumnModelActionsColumnSupport;
use libraries\NewObjectTable;
use libraries\Utilities;

class EntityTable extends NewObjectTable implements TableColumnModelActionsColumnSupport, TableFormActionsSupport
{
    const TABLE_IDENTIFIER = Manager :: PARAM_LOCATION_ENTITY_RIGHT_GROUP_ID;
    const DEFAULT_ROW_COUNT = 20;

    public function get_implemented_form_actions()
    {
        $actions = new TableFormActions(__NAMESPACE__);
        $actions->add_form_action(
            new TableFormAction(
                array(Manager :: PARAM_ACTION => Manager :: ACTION_DELETE),
                Translation :: get('RemoveSelected', null, Utilities :: COMMON_LIBRARIES)));
        return $actions;
    }
}
