<?php
namespace application\atlantis\application;

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
        if ($this->get_component()->get_user()->is_platform_admin())
        {
            $actions->add_form_action(
                new ObjectTableFormAction(
                    Manager :: ACTION_DELETE,
                    Translation :: get('RemoveSelected', null, Utilities :: COMMON_LIBRARIES)));
        }
        return $actions;
    }
}
