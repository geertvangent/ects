<?php
namespace application\atlantis\application;

use libraries\format\TableFormActionsSupport;
use libraries\format\Table;
use libraries\utilities\Utilities;
use libraries\platform\Translation;
use libraries\format\TableFormAction;
use libraries\format\TableFormActions;

class ApplicationTable extends Table implements TableFormActionsSupport
{
    const TABLE_IDENTIFIER = Manager :: PARAM_APPLICATION_ID;
    const DEFAULT_ROW_COUNT = 20;

    public function get_implemented_form_actions()
    {
        $actions = new TableFormActions(__NAMESPACE__);
        if ($this->get_component()->get_user()->is_platform_admin())
        {
            $actions->add_form_action(
                new TableFormAction(
                    array(Manager :: PARAM_ACTION => Manager :: ACTION_DELETE),
                    Translation :: get('RemoveSelected', null, Utilities :: COMMON_LIBRARIES)));
        }
        return $actions;
    }
}
