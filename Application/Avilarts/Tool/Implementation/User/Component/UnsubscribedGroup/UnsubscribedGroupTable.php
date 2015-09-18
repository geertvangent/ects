<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\User\Component\UnsubscribedGroup;


use Ehb\Application\Avilarts\Tool\Implementation\User\Manager;
use Chamilo\Libraries\Format\Table\Extension\DataClassTable\DataClassTable;
use Chamilo\Libraries\Format\Table\FormAction\TableFormAction;
use Chamilo\Libraries\Format\Table\FormAction\TableFormActions;
use Chamilo\Libraries\Format\Table\Interfaces\TableFormActionsSupport;
use Chamilo\Libraries\Platform\Translation;

/**
 * * *************************************************************************** Table to display a list of groups not
 * in a course.
 * 
 * @author Stijn Van Hoecke ****************************************************************************
 */
class UnsubscribedGroupTable extends DataClassTable implements TableFormActionsSupport
{

    public function get_implemented_form_actions()
    {
        if ($this->get_component()->is_allowed(\Ehb\Application\Avilarts\Rights\Rights :: EDIT_RIGHT))
        {
            // add subscribe options
            $actions = new TableFormActions(__NAMESPACE__);
            
            $actions->add_form_action(
                new TableFormAction(
                    array(
                        \Ehb\Application\Avilarts\Tool\Manager :: PARAM_ACTION => Manager :: ACTION_SUBSCRIBE_GROUPS), 
                    Translation :: get('SubscribeSelectedGroups'), 
                    false));
            
            return $actions;
        }
    }
}