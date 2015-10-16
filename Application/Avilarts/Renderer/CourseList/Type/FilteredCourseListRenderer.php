<?php
namespace Ehb\Application\Avilarts\Renderer\CourseList\Type;

use Ehb\Application\Avilarts\Course\Storage\DataClass\Course;
use Ehb\Application\Avilarts\Renderer\CourseList\CourseListRenderer;
use Ehb\Application\Avilarts\Storage\DataClass\CourseTypeUserCategory;
use Ehb\Application\Avilarts\Storage\DataClass\CourseTypeUserCategoryRelCourse;
use Ehb\Application\Avilarts\Storage\DataManager;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Storage\Query\Condition\AndCondition;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\Condition\InCondition;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;

/**
 * Course list renderer to render the course list filtered by a given course type and user course category
 *
 * @author Sven Vanpoucke
 */
class FilteredCourseListRenderer extends CourseListRenderer
{

    /**
     * **************************************************************************************************************
     * Properties *
     * **************************************************************************************************************
     */

    /**
     * The filtered course type id
     *
     * @var int
     */
    private $course_type_id;

    /**
     * The filtered user course category id
     *
     * @var int
     */
    private $user_course_category_id;

    /**
     * **************************************************************************************************************
     * Inherited Functionality *
     * **************************************************************************************************************
     */

    /**
     * Shows a course list filtered on a given course type and user course category id
     *
     * @param mixed $parent
     * @param string $target
     * @param int $course_type_id - [OPTIONAL] default 0
     * @param int $user_course_category_id - [OPTIONAL] default null
     */
    public function __construct($parent, $target = '', $course_type_id = 0, $user_course_category_id = null)
    {
        parent :: __construct($parent, $target);

        $this->set_course_type_id($course_type_id);
        $this->set_user_course_category_id($user_course_category_id);
    }

    /**
     * Returns the conditions needed to retrieve the courses
     *
     * @return Condition
     */
    protected function get_retrieve_courses_condition()
    {
        $course_type_id = $this->get_course_type_id();

        $conditions = array();

        $conditions[] = new EqualityCondition(
            new PropertyConditionVariable(Course :: class_name(), Course :: PROPERTY_COURSE_TYPE_ID),
            new StaticConditionVariable($course_type_id));

        $user_course_category_id = $this->get_user_course_category_id();
        if (! is_null($user_course_category_id))
        {
            $course_user_category_conditions = array();

            $course_user_category_conditions[] = new EqualityCondition(
                new PropertyConditionVariable(
                    CourseTypeUserCategory :: class_name(),
                    CourseTypeUserCategory :: PROPERTY_COURSE_USER_CATEGORY_ID),
                new StaticConditionVariable($user_course_category_id));

            $course_user_category_conditions[] = new EqualityCondition(
                new PropertyConditionVariable(
                    CourseTypeUserCategory :: class_name(),
                    CourseTypeUserCategory :: PROPERTY_COURSE_TYPE_ID),
                new StaticConditionVariable($course_type_id));

            $course_user_category_condition = new AndCondition($course_user_category_conditions);

            // retrieve course user categories

            $course_type_user_categories = DataManager :: retrieves(
                CourseTypeUserCategory :: class_name(),
                new DataClassRetrievesParameters($course_user_category_condition));

            $course_type_user_category_ids = array();
            while ($course_type_user_category = $course_type_user_categories->next_result())
            {
                $course_type_user_category_ids[] = $course_type_user_category->get_id();
            }

            $course_type_user_category_condition = new InCondition(
                new PropertyConditionVariable(
                    CourseTypeUserCategoryRelCourse :: class_name(),
                    CourseTypeUserCategoryRelCourse :: PROPERTY_COURSE_TYPE_USER_CATEGORY_ID),
                $course_type_user_category_ids);

            $course_type_user_category_rel_courses = DataManager :: retrieves(
                CourseTypeUserCategoryRelCourse :: class_name(),
                new DataClassRetrievesParameters($course_type_user_category_condition));

            $course_type_user_category_rel_course_ids = array();
            while ($course_type_user_category_rel_course = $course_type_user_category_rel_courses->next_result())
            {
                $course_type_user_category_rel_course_ids[] = $course_type_user_category_rel_course->get_course_id();
            }

            $conditions[] = new Incondition(Course :: PROPERTY_ID, $course_type_user_category_rel_course_ids);
        }

        return new AndCondition($conditions);
    }

    /**
     * **************************************************************************************************************
     * Getters & Setters *
     * **************************************************************************************************************
     */

    /**
     * Returns the course type id
     *
     * @return int
     */
    public function get_course_type_id()
    {
        return $this->course_type_id;
    }

    /**
     * Sets the course type id
     *
     * @param int $course_type_id
     */
    public function set_course_type_id($course_type_id)
    {
        $this->course_type_id = $course_type_id;
    }

    /**
     * Returns the user course category id
     *
     * @return int
     */
    public function get_user_course_category_id()
    {
        return $this->user_course_category_id;
    }

    /**
     * Sets the user course category id
     *
     * @param int $user_course_category_id
     */
    public function set_user_course_category_id($user_course_category_id)
    {
        $this->user_course_category_id = $user_course_category_id;
    }

    /**
     * Defines the display of the message when there are no courses to display.
     */
    protected function get_no_courses_message_as_html()
    {
        return '<div style="text-align:center">' . Translation :: get('NoCoursesMatchSearchCriteria') . '</div>';
    }
}
