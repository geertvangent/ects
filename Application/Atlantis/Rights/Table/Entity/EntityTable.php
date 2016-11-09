<?php
namespace Ehb\Application\Atlantis\Rights\Table\Entity;

use Chamilo\Libraries\Format\Table\FormAction\TableFormAction;
use Chamilo\Libraries\Format\Table\FormAction\TableFormActions;
use Chamilo\Libraries\Format\Table\Interfaces\TableColumnModelActionsColumnSupport;
use Chamilo\Libraries\Format\Table\Interfaces\TableFormActionsSupport;
use Chamilo\Libraries\Format\Table\Table;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Utilities\Utilities;
use Ehb\Application\Atlantis\Rights\Manager;

class EntityTable extends Table implements TableColumnModelActionsColumnSupport, TableFormActionsSupport
{
    const TABLE_IDENTIFIER = Manager::PARAM_LOCATION_ENTITY_RIGHT_GROUP_ID;
    const DEFAULT_ROW_COUNT = 20;

    public function get_implemented_form_actions()
    {
        $actions = new TableFormActions(__NAMESPACE__, self::TABLE_IDENTIFIER);
        $actions->add_form_action(
            new TableFormAction(
                array(Manager::PARAM_ACTION => Manager::ACTION_DELETE), 
                Translation::get('RemoveSelected', null, Utilities::COMMON_LIBRARIES)));
        return $actions;
    }
}
