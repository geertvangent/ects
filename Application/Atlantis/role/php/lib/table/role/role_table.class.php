<?php
namespace application\atlantis\role;

use common\libraries\NewObjectTableFormActionsSupport;
use common\libraries\NewObjectTable;
use common\libraries\Utilities;
use common\libraries\Translation;
use common\libraries\ObjectTableFormAction;
use common\libraries\ObjectTableFormActions;
use common\libraries\PlatformSetting;

class RoleTable extends NewObjectTable implements NewObjectTableFormActionsSupport
{
    const TABLE_IDENTIFIER = Manager :: PARAM_ROLE_ID;
    const DEFAULT_ROW_COUNT = 20;

    function get_implemented_form_actions()
    {
        $actions = new ObjectTableFormActions(__NAMESPACE__, Manager :: PARAM_ACTION);
        if ($this->get_component()->get_user()->is_platform_admin())
        {
            $actions->add_form_action(new ObjectTableFormAction(Manager :: ACTION_DELETE, Translation :: get('RemoveSelected', null, Utilities :: COMMON_LIBRARIES)));
        }
        return $actions;
    }
}
?>