<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\CourseGroup\Table\Subscribed;


use Ehb\Application\Avilarts\Tool\Implementation\CourseGroup\Manager;
use Chamilo\Libraries\Format\Table\Extension\DataClassTable\DataClassTable;
use Chamilo\Libraries\Format\Table\FormAction\TableFormAction;
use Chamilo\Libraries\Format\Table\FormAction\TableFormActions;
use Chamilo\Libraries\Format\Table\Interfaces\TableFormActionsSupport;
use Chamilo\Libraries\Platform\Translation;

/**
 * $Id: course_group_subscribed_user_browser_table.class.php 216 2009-11-13 14:08:06Z kariboe $
 * 
 * @package application.lib.weblcms.tool.course_group.component.user_table
 */
class SubscribedUserTable extends DataClassTable implements TableFormActionsSupport
{
    const TABLE_IDENTIFIER = Manager :: PARAM_UNSUBSCRIBE_USERS;

    public function get_implemented_form_actions()
    {
        $actions = new TableFormActions(__NAMESPACE__, self :: TABLE_IDENTIFIER);
        $browser = $this->get_component();
        if ($browser->is_allowed(\Ehb\Application\Avilarts\Rights\Rights :: EDIT_RIGHT))
        {
            $actions->add_form_action(
                new TableFormAction(
                    array(\Ehb\Application\Avilarts\Manager :: PARAM_ACTION => Manager :: ACTION_UNSUBSCRIBE), 
                    Translation :: get('UnsubscribeUsers')));
        }
        
        return $actions;
    }
}