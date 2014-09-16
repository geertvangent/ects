<?php
namespace application\atlantis\rights;

use libraries\Translation;
use libraries\ObjectTableFormAction;
use libraries\ObjectTableFormActions;
use libraries\NewObjectTableFormActionsSupport;
use libraries\NewObjectTableColumnModelActionsColumnSupport;
use libraries\NewObjectTable;
use libraries\Utilities;

class EntityTable extends NewObjectTable implements NewObjectTableColumnModelActionsColumnSupport, 
    NewObjectTableFormActionsSupport
{
    const TABLE_IDENTIFIER = Manager :: PARAM_LOCATION_ENTITY_RIGHT_GROUP_ID;
    const DEFAULT_ROW_COUNT = 20;

    public function get_implemented_form_actions()
    {
        $actions = new ObjectTableFormActions(__NAMESPACE__, Manager :: PARAM_ACTION);
        $actions->add_form_action(
            new ObjectTableFormAction(
                Manager :: ACTION_DELETE, 
                Translation :: get('RemoveSelected', null, Utilities :: COMMON_LIBRARIES)));
        return $actions;
    }
}
