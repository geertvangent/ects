<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\User\Component\Subscribed;

use Chamilo\Libraries\Format\Table\Extension\RecordTable\RecordTable;
use Chamilo\Libraries\Format\Table\FormAction\TableFormAction;
use Chamilo\Libraries\Format\Table\FormAction\TableFormActions;
use Chamilo\Libraries\Format\Table\Interfaces\TableFormActionsSupport;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Platform\Translation;
use Ehb\Application\Avilarts\Tool\Implementation\User\Manager;

/**
 * Table to display a list of users in a course (direct subscribed or platform group).
 * 
 * @author Stijn Van Hoecke
 * @author Sven Vanpoucke - Hogeschool Gent - Refactoring from ObjectTable to RecordTable
 */
class SubscribedUserTable extends RecordTable implements TableFormActionsSupport
{
    const TABLE_IDENTIFIER = Manager :: PARAM_OBJECTS;

    /**
     * **************************************************************************************************************
     * Inherited Functionality *
     * **************************************************************************************************************
     */
    public function get_implemented_form_actions()
    {
        $actions = new TableFormActions(__NAMESPACE__, self :: TABLE_IDENTIFIER);
        
        if ($this->get_component()->is_course_admin($this->get_component()->get_user()))
        {
            // if we are not editing groups
            if (! Request :: get(\Ehb\Application\Avilarts\Manager :: PARAM_GROUP))
            {
                $actions->add_form_action(
                    new TableFormAction(
                        array(
                            \Ehb\Application\Avilarts\Tool\Manager :: PARAM_ACTION => Manager :: ACTION_UNSUBSCRIBE), 
                        Translation :: get('UnsubscribeSelected'), 
                        false));
                
                // make teacher
                $actions->add_form_action(
                    new TableFormAction(
                        array(
                            \Ehb\Application\Avilarts\Tool\Manager :: PARAM_ACTION => Manager :: ACTION_CHANGE_USER_STATUS_TEACHER), 
                        Translation :: get('MakeTeacher'), 
                        false));
                
                // make student
                $actions->add_form_action(
                    new TableFormAction(
                        array(
                            \Ehb\Application\Avilarts\Tool\Manager :: PARAM_ACTION => Manager :: ACTION_CHANGE_USER_STATUS_STUDENT), 
                        Translation :: get('MakeStudent'), 
                        false));
            }
        }
        
        return $actions;
    }
}