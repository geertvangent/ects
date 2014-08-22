<?php
namespace application\atlantis\user_group;

use libraries\NewObjectTableFormActionsSupport;
use libraries\NewObjectTable;
use libraries\Utilities;
use libraries\Translation;
use libraries\ObjectTableFormAction;
use libraries\ObjectTableFormActions;

class ApplicationTable extends NewObjectTable implements NewObjectTableFormActionsSupport
{
    const TABLE_IDENTIFIER = Manager :: PARAM_APPLICATION_ID;
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
