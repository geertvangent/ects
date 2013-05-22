<?php
namespace application\atlantis\rights;

use common\libraries\Translation;
use common\libraries\ObjectTableFormAction;
use common\libraries\ObjectTableFormActions;
use common\libraries\NewObjectTableFormActionsSupport;
use common\libraries\NewObjectTableColumnModelActionsColumnSupport;
use common\libraries\NewObjectTable;
use common\libraries\Utilities;

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
