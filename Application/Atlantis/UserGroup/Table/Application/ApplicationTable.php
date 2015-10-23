<?php
namespace Ehb\Application\Atlantis\UserGroup\Table\Application;

use Chamilo\Libraries\Format\Table\FormAction\TableFormAction;
use Chamilo\Libraries\Format\Table\FormAction\TableFormActions;
use Chamilo\Libraries\Format\Table\Interfaces\TableFormActionsSupport;
use Chamilo\Libraries\Format\Table\Table;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Utilities\Utilities;
use Ehb\Application\Atlantis\UserGroup\Manager;

class ApplicationTable extends Table implements TableFormActionsSupport
{
    const TABLE_IDENTIFIER = Manager :: PARAM_APPLICATION_ID;
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
