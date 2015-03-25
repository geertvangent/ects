<?php
namespace Ehb\Core\Metadata\Vocabulary\Table\User;

use Ehb\Core\Metadata\Vocabulary\Manager;
use Chamilo\Libraries\Format\Table\FormAction\TableFormAction;
use Chamilo\Libraries\Format\Table\FormAction\TableFormActions;
use Chamilo\Libraries\Format\Table\Interfaces\TableFormActionsSupport;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Utilities\Utilities;
use Chamilo\Libraries\Format\Table\Extension\RecordTable\RecordTable;

/**
 * Table for the schema
 *
 * @author Hans De Bisschop <hans.de.bisschop@ehb.be>
 */
class UserTable extends RecordTable implements TableFormActionsSupport
{
    const TABLE_IDENTIFIER = Manager :: PARAM_USER_ID;

    /**
     * Returns the implemented form actions
     *
     * @return TableFormActions
     */
    public function get_implemented_form_actions()
    {
        $actions = new TableFormActions(__NAMESPACE__);

        $actions->add_form_action(
            new TableFormAction(
                array(
                    Manager :: PARAM_ACTION => Manager :: ACTION_DELETE,
                    \Ehb\Core\Metadata\Element\Manager :: PARAM_ELEMENT_ID => $this->get_component()->getSelectedElementId()),
                Translation :: get('RemoveSelected', null, Utilities :: COMMON_LIBRARIES)));

        return $actions;
    }
}