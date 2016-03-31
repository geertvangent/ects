<?php
namespace Ehb\Application\Avilarts\Course\Component;

use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
use Chamilo\Libraries\Platform\Translation;
use Ehb\Application\Avilarts\Course\Manager;
use Ehb\Application\Avilarts\Course\Storage\DataClass\CourseUserRelation;
use Ehb\Application\Avilarts\Rights\CourseManagementRights;

/**
 * This class describes an action to subscribe to a course
 *
 * @package \application\Avilarts\course
 * @author Yannick & Tristan
 * @author Sven Vanpoucke - Hogeschool Gent - Refactoring
 * @author Anthony Hurst (Hogeschool Gent)
 */
class SubscribeComponent extends Manager
{

    /**
     * **************************************************************************************************************
     * Inherited Functionality *
     * **************************************************************************************************************
     */

    /**
     * Runs this component and displays its output.
     */
    public function run()
    {
        $failures = 0;

        $course_management_rights = CourseManagementRights :: get_instance();
        $course_ids = $this->get_selected_course_ids();
        $this->set_parameter(self :: PARAM_COURSE_ID, $course_ids);

        foreach ($course_ids as $course_id)
        {
            if ($course_management_rights->is_allowed(CourseManagementRights :: DIRECT_SUBSCRIBE_RIGHT, $course_id))
            {
                $course_user_relation = new CourseUserRelation();
                $course_user_relation->set_user_id($this->get_user_id());
                $course_user_relation->set_course_id($course_id);
                $course_user_relation->set_status(CourseUserRelation :: STATUS_STUDENT);

                if (! $course_user_relation->create())
                {
                    $failures ++;
                }
            }
            else
            {
                $failures ++;
            }
        }

        $message = $this->get_result(
            $failures,
            count($course_ids),
            'UserNotSubscribedToSelectedCourses',
            'UserNotSubscribedToSelectedCourse',
            'UserSubscribedToSelectedCourses',
            'UserSubscribedToSelectedCourse');

        $this->redirect(
            $message,
            ($failures > 0),
            array(self :: PARAM_ACTION => self :: ACTION_BROWSE_UNSUBSCRIBED_COURSES),
            array(self :: PARAM_COURSE_ID));
    }

    /**
     * Breadcrumbs are built semi automatically with the given application, subapplication, component... Use this
     * function to add other breadcrumbs between the application / subapplication and the current component
     *
     * @param $breadcrumbtrail \libraries\format\BreadcrumbTrail
     */
    public function add_additional_breadcrumbs(BreadcrumbTrail $breadcrumbtrail)
    {
        $breadcrumbtrail->add_help('weblcms_course_subscriber');
        $breadcrumbtrail->add(
            new Breadcrumb(
                $this->get_browse_course_url(),
                Translation :: get('CourseManagerBrowseUnsubscribedCoursesComponent')));
    }
}
