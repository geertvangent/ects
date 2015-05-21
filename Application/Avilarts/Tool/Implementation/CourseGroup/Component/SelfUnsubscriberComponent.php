<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\CourseGroup\Component;

use Ehb\Application\Avilarts\Rights\WeblcmsRights;
use Ehb\Application\Avilarts\Tool\Implementation\CourseGroup\Manager;
use Chamilo\Libraries\Platform\Translation;

/**
 * $Id: course_group_self_unsubscriber.class.php 216 2009-11-13 14:08:06Z kariboe $
 * 
 * @package application.lib.weblcms.tool.course_group.component
 */
class SelfUnsubscriberComponent extends Manager
{

    public function run()
    {
        $course_group = $this->get_course_group();
        $user = $this->get_user();
        
        if (! $this->is_allowed(WeblcmsRights :: EDIT_RIGHT) && $course_group->is_self_unregistration_allowed() &&
             $course_group->is_member($user) && $this->get_user()->get_id() == $user->get_id())
        {
            $course_group->unsubscribe_users($this->get_user_id());
            $this->redirect(Translation :: get('UserUnsubscribed'), false, array('tool_action' => null));
        }
        else
        {
            throw new \Chamilo\Libraries\Architecture\Exceptions\NotAllowedException();
        }
    }
}
