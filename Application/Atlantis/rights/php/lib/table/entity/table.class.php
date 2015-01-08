<?php
namespace application\atlantis\rights;

use libraries\platform\translation\Translation;
use libraries\format\TableFormAction;
use libraries\format\TableFormActions;
use libraries\format\TableFormActionsSupport;
use libraries\format\TableColumnModelActionsColumnSupport;
use libraries\format\Table;
use libraries\utilities\Utilities;

class EntityTable extends Table implements TableColumnModelActionsColumnSupport, TableFormActionsSupport
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
