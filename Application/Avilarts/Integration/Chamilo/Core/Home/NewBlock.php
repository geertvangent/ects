<?php
namespace Ehb\Application\Avilarts\Integration\Chamilo\Core\Home;

use Ehb\Application\Avilarts\Course\Storage\DataManager as CourseDataManager;
use Ehb\Application\Avilarts\Storage\DataClass\ContentObjectPublication;
use Chamilo\Core\Repository\ContentObject\Announcement\Storage\DataClass\Announcement;
use Chamilo\Core\Repository\ContentObject\Assignment\Storage\DataClass\Assignment;
use Chamilo\Core\Repository\ContentObject\File\Storage\DataClass\File;
use Chamilo\Core\Repository\ContentObject\Webpage\Storage\DataClass\Webpage;
use Chamilo\Libraries\Platform\Configuration\PlatformSetting;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Storage\Query\Condition\AndCondition;
use Chamilo\Libraries\Storage\Query\Condition\InequalityCondition;
use Chamilo\Libraries\Storage\Query\OrderBy;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Chamilo\Libraries\Utilities\Utilities;

/**
 * Description of weblcms_new_block
 *
 * @author Anthony Hurst (Hogeschool Gent)
 */
class NewBlock extends Block
{
    const TOOL_ANNOUNCEMENT = 'announcement';
    const TOOL_ASSIGNMENT = 'assignment';
    const TOOL_DOCUMENT = 'document';
    const OVERSIZED_SETTING = 'oversized_new_list_threshold';
    const FORCE_OVERSIZED = 'force_oversized_newblocks';
    const DO_FORCE_OVERSIZED = '1';
    const OVERSIZED_WARNING = 'oversized';

    private $courses;

    public function get_content($tool)
    {
        // All user courses
        $user_courses = CourseDataManager :: retrieve_all_courses_from_user($this->get_user());

        $threshold = intval(PlatformSetting :: get(self :: OVERSIZED_SETTING, 'Ehb\Application\Avilarts'));

        if ($threshold !== 0 && Request :: get(self :: FORCE_OVERSIZED) != self :: DO_FORCE_OVERSIZED &&
             $user_courses->size() > $threshold)
        {
            $this->courses = array();
            return self :: OVERSIZED_WARNING;
        }

        $this->courses = array();

        $course_settings_controller = \Ehb\Application\Avilarts\CourseSettingsController :: get_instance();
        $unique_publications = array();
        while ($course = $user_courses->next_result())
        {
            $this->courses[$course->get_id()] = $course;

            if ($course_settings_controller->get_course_setting(
                $course->get_id(),
                \Ehb\Application\Avilarts\CourseSettingsConnector :: VISIBILITY) == 1)
            {
                $condition = $this->get_publication_conditions($course, $tool);
                $course_module_id = \Ehb\Application\Avilarts\Storage\DataManager :: retrieve_course_tool_by_name($tool)->get_id();
                $location = \Ehb\Application\Avilarts\Rights\Rights :: get_instance()->get_weblcms_location_by_identifier_from_courses_subtree(
                    \Ehb\Application\Avilarts\Rights\Rights :: TYPE_COURSE_MODULE,
                    $course_module_id,
                    $course->get_id());

                $entities = array();
                $entities[\Ehb\Application\Avilarts\Rights\Entities\CourseGroupEntity :: ENTITY_TYPE] = \Ehb\Application\Avilarts\Rights\Entities\CourseGroupEntity :: get_instance(
                    $course->get_id());
                $entities[\Ehb\Application\Avilarts\Rights\Entities\CourseUserEntity :: ENTITY_TYPE] = \Ehb\Application\Avilarts\Rights\Entities\CourseUserEntity :: get_instance();
                $entities[\Ehb\Application\Avilarts\Rights\Entities\CoursePlatformGroupEntity :: ENTITY_TYPE] = \Ehb\Application\Avilarts\Rights\Entities\CoursePlatformGroupEntity :: get_instance();

                $publications = \Ehb\Application\Avilarts\Storage\DataManager :: retrieve_content_object_publications_with_view_right_granted_in_category_location(
                    $location,
                    $entities,
                    $condition,
                    new OrderBy(
                        new PropertyConditionVariable(
                            \Ehb\Application\Avilarts\Storage\DataClass\ContentObjectPublication :: class_name(),
                            \Ehb\Application\Avilarts\Storage\DataClass\ContentObjectPublication :: PROPERTY_DISPLAY_ORDER_INDEX)));

                if ($publications == 0)
                {
                    continue;
                }
                foreach ($publications->as_array() as $publication)
                {
                    $unique_publications[$course->get_id() . '.' . $publication[ContentObjectPublication :: PROPERTY_ID]] = $publication;
                }
            }
        }
        return $unique_publications;
    }

    private function get_publication_conditions($course, $tool)
    {
        $type = null;
        switch ($tool)
        {
            case self :: TOOL_ANNOUNCEMENT :
                $type = Announcement :: class_name();
                break;
            case self :: TOOL_ASSIGNMENT :
                $type = Assignment :: class_name();
                break;
            case self :: TOOL_DOCUMENT :
                $type = array(File :: class_name(), Webpage :: class_name());
                break;
        }
        $last_visit_date = \Ehb\Application\Avilarts\Storage\DataManager :: get_last_visit_date(
            $course->get_id(),
            $this->get_user_id(),
            $tool,
            0);

        $conditions = array();
        $conditions[] = \Ehb\Application\Avilarts\Storage\DataManager :: get_publications_condition(
            $course,
            $this->get_user(),
            $tool,
            $type);
        $conditions[] = new InequalityCondition(
            new PropertyConditionVariable(
                \Ehb\Application\Avilarts\Storage\DataClass\ContentObjectPublication :: class_name(),
                \Ehb\Application\Avilarts\Storage\DataClass\ContentObjectPublication :: PROPERTY_PUBLICATION_DATE),
            InequalityCondition :: GREATER_THAN_OR_EQUAL,
            new StaticConditionVariable($last_visit_date));
        return new AndCondition($conditions);
    }

    protected function get_course_by_id($course_id)
    {
        return $this->courses[$course_id];
    }

    public function get_oversized_warning()
    {
        return '<div class="warning-message" style="width: auto; margin: 0 0 1em 0; position: static;">' .
             Translation :: get('OversizedWarning', null, Utilities :: COMMON_LIBRARIES) . ' <a href="?' .
             Utilities :: get_current_query_string(array(self :: FORCE_OVERSIZED => self :: DO_FORCE_OVERSIZED)) . '">' .
             Translation :: get('ForceOversized', null, Utilities :: COMMON_LIBRARIES) . '</a></div>';
    }
}
