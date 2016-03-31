<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\CourseGroup;

use Chamilo\Libraries\Architecture\Application\ApplicationConfigurationInterface;
use Chamilo\Libraries\Platform\Session\Request;
use Ehb\Application\Avilarts\Tool\Implementation\CourseGroup\Storage\DataClass\CourseGroup;

/**
 * $Id: course_group_tool.class.php 216 2009-11-13 14:08:06Z kariboe $
 *
 * @package application.lib.weblcms.tool.course_group
 */
/**
 * This tool allows a course_group to publish course_groups in his or her course.
 */
abstract class Manager extends \Ehb\Application\Avilarts\Tool\Manager
{
    const TOOL_NAME = 'course_group';
    const PARAM_COURSE_GROUP_ACTION = 'tool_action';
    const PARAM_DELETE_COURSE_GROUPS = 'delete_course_groups';
    const PARAM_UNSUBSCRIBE_USERS = 'unsubscribe_users';
    const ACTION_SUBSCRIBE = 'SubscribeBrowser';
    const ACTION_UNSUBSCRIBE = 'UnsubscribeBrowser';
    const ACTION_ADD_COURSE_GROUP = 'Creator';
    const ACTION_EDIT_COURSE_GROUP = 'Editor';
    const ACTION_DELETE_COURSE_GROUP = 'Deleter';
    const ACTION_USER_SELF_SUBSCRIBE = 'SelfSubscriber';
    const ACTION_USER_SELF_UNSUBSCRIBE = 'SelfUnsubscriber';
    const ACTION_VIEW_GROUPS = 'Browser';
    const ACTION_MANAGE_SUBSCRIPTIONS = 'ManageSubscriptions';
    const ACTION_SUBSCRIPTIONS_OVERVIEW = 'SubscriptionsOverviewer';
    const ACTION_EXPORT_SUBSCRIPTIONS_OVERVIEW = 'Exporter';
    const PARAM_COURSE_GROUP = 'course_group';
    const PARAM_TAB = 'tab';

    /**
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Chamilo\Core\User\Storage\DataClass\User $user
     * @param \Chamilo\Libraries\Architecture\Application\Application $parent
     */
    public function __construct(ApplicationConfigurationInterface $applicationConfiguration)
    {
        parent :: __construct($applicationConfiguration);
        $this->set_parameter(self :: PARAM_COURSE_GROUP, Request :: get(self :: PARAM_COURSE_GROUP));
    }

    public function get_course_group()
    {
        $course_group_id = Request :: get(self :: PARAM_COURSE_GROUP);

        return \Ehb\Application\Avilarts\Storage\DataManager :: retrieve_by_id(
            CourseGroup :: class_name(),
            $course_group_id);
    }
}
