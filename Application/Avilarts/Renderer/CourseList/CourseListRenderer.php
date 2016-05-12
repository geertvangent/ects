<?php
namespace Ehb\Application\Avilarts\Renderer\CourseList;

use Ehb\Application\Avilarts\CourseSettingsConnector;
use Ehb\Application\Avilarts\CourseSettingsController;
use Ehb\Application\Avilarts\Course\Storage\DataClass\Course;
use Ehb\Application\Avilarts\Course\Storage\DataManager as CourseDataManager;
use Ehb\Application\Avilarts\Manager;
use Ehb\Application\Avilarts\Storage\DataClass\CourseSetting;
use Ehb\Application\Avilarts\Storage\DataClass\CourseTool;
use Ehb\Application\Avilarts\Storage\DataManager;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Configuration\PlatformSetting;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Utilities\Utilities;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;

/**
 * Course list renderer to render the course list (used in courses home, courses sorter, courses block...)
 *
 * @author Sven Vanpoucke
 */
class CourseListRenderer
{

    /**
     * The parent on which the course list renderer is running
     */
    private $parent;

    /**
     * Show the what's new icons or not
     *
     * @var boolean
     */
    private $new_publication_icons;

    /**
     * Link target.
     *
     * @var string
     */
    private $target = '';

    private $tools;

    public function __construct($parent, $target = '')
    {
        $this->parent = $parent;
        $this->new_publication_icons = false;
        $this->target = $target;
        $this->tools = DataManager :: retrieves(CourseTool :: class_name(), new DataClassRetrievesParameters())->as_array();
    }

    public function get_parent()
    {
        return $this->parent;
    }

    public function set_parent($parent)
    {
        $this->parent = $parent;
    }

    public function get_user()
    {
        return $this->get_parent()->get_user();
    }

    public function show_new_publication_icons()
    {
        $this->new_publication_icons = true;
    }

    public function hide_new_publication_icons()
    {
        $this->new_publication_icons = false;
    }

    public function get_new_publication_icons()
    {
        return $this->new_publication_icons;
    }

    public function get_target()
    {
        return $this->target;
    }

    public function set_target($target)
    {
        $this->target = $target;
    }

    /**
     * Renders the course list
     */
    public function render()
    {
        echo $this->as_html();
    }

    /**
     * Returns the course list as html
     */
    public function as_html()
    {
        return $this->display_courses();
    }

    /**
     * Retrieves the courses for the user
     */
    protected function retrieve_courses()
    {
        return CourseDataManager :: retrieve_all_courses_from_user(
            $this->get_user(),
            $this->get_retrieve_courses_condition());
    }

    public function get_courses()
    {
        return $this->retrieve_courses();
    }

    /**
     * Returns the conditions needed to retrieve the courses
     */
    protected function get_retrieve_courses_condition()
    {
        return null;
    }

    /**
     * Displays the courses
     */
    protected function display_courses()
    {
        $html = array();
        $courses = $this->retrieve_courses();
        $target = $this->target ? ' target="' . $this->target . '" ' : '';

        if ($courses->size() > 0)
        {
            $html[] = '<ul style="padding: 0px; margin: 0px 0px 0px 15px;">';

            $course_settings_controller = CourseSettingsController :: get_instance();

            while ($course = $courses->next_result())
            {
                $id = $course->get_id();

                $course_access = $course_settings_controller->get_course_setting(
                    $id,
                    CourseSettingsConnector :: COURSE_ACCESS);

                $course_visible = $course_settings_controller->get_course_setting(
                    $id,
                    CourseSettingsConnector :: VISIBILITY);

                if (($course_access == CourseSettingsConnector :: COURSE_ACCESS_CLOSED || ! $course_visible) &&
                     ! $course->is_course_admin($this->get_user()))
                {
                    continue;
                }

                $html[] = '<li><a href="' . htmlspecialchars($this->get_course_url($course)) . '"' . $target . '>' .
                     htmlspecialchars($course->get_title()) . '</a>';

                if ($this->get_new_publication_icons())
                {
                    $html[] = $this->display_new_publication_icons($course);
                }

                $html[] = '</li>';
            }
            $html[] = '</ul>';
        }
        else
        {
            $html[] = $this->get_no_courses_message_as_html();
        }

        return implode($html, "\n");
    }

    /**
     * Defines the display of the message when there are no courses to display.
     */
    protected function get_no_courses_message_as_html()
    {
        return '<div class="normal-message">' . Translation :: get('NoCourses') . '</div>';
    }

    /**
     * Displays the what's new icons
     *
     * @param $course Course
     */
    protected function display_new_publication_icons(Course $course)
    {
        $html = array();
        $target = $this->target ? ' target="' . $this->target . '" ' : '';

        $course_settings_controller = CourseSettingsController :: get_instance();

        foreach ($this->tools as $tool)
        {
            $active = $course_settings_controller->get_course_setting(
                $course->get_id(),
                CourseSetting :: COURSE_SETTING_TOOL_ACTIVE,
                $tool->get_id());
            $visible = $course_settings_controller->get_course_setting(
                $course->get_id(),
                CourseSetting :: COURSE_SETTING_TOOL_VISIBLE,
                $tool->get_id());

            $toolHasNewPublications = DataManager :: tool_has_new_publications(
                $tool->get_name(),
                $this->get_user(),
                $course);

            if ($active && $visible && $toolHasNewPublications)
            {

                $html[] = '<a href="' . htmlspecialchars($this->get_tool_url($tool->get_name(), $course)) . '"' . $target .
                     '><img src="' . htmlspecialchars(
                        Theme :: getInstance()->getImagePath(
                            \Ehb\Application\Avilarts\Tool\Manager :: get_tool_type_namespace($tool->get_name()),
                            'Logo/' . Theme :: ICON_MINI . 'New')) . '" alt="' .
                     htmlspecialchars(Translation :: get('New', null, Utilities :: COMMON_LIBRARIES)) . '"/></a>';
            }
        }
        return implode($html, "\n");
    }

    /**
     * Gets the url from the given course
     *
     * @param $course Course
     */
    public function get_course_url(Course $course)
    {
        $parameters = array();
        $parameters[Manager :: PARAM_CONTEXT] = Manager :: context();
        $parameters[Manager :: PARAM_ACTION] = Manager :: ACTION_VIEW_COURSE;
        $parameters[Manager :: PARAM_COURSE] = $course->get_id();
        return $this->get_parent()->get_link($parameters);
    }

    /**
     * Gets the url from the given tool in the given course
     *
     * @param $tool String
     * @param $course Course
     */
    public function get_tool_url($tool, Course $course)
    {
        $parameters = array();
        $parameters[Manager :: PARAM_CONTEXT] = Manager :: context();
        $parameters[Manager :: PARAM_ACTION] = Manager :: ACTION_VIEW_COURSE;
        $parameters[Manager :: PARAM_COURSE] = $course->get_id();
        $parameters[Manager :: PARAM_TOOL] = $tool;
        return $this->get_parent()->get_link($parameters);
    }
}
