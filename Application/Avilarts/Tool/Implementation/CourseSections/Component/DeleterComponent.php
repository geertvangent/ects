<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\CourseSections\Component;

use Ehb\Application\Avilarts\Storage\DataClass\CourseSection;
use Ehb\Application\Avilarts\Tool\Implementation\CourseSections\Manager;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Platform\Translation;

/**
 * $Id: course_sections_deleter.class.php 216 2009-11-13 14:08:06Z kariboe $
 *
 * @package application.lib.weblcms.tool.course_sections.component
 */
class DeleterComponent extends Manager
{

    /**
     * Runs this component and displays its output.
     */
    public function run()
    {
        $user = $this->get_user();

        if (! $this->get_course()->is_course_admin($this->get_parent()->get_user()))
        {
            throw new \Chamilo\Libraries\Architecture\Exceptions\NotAllowedException();
        }

        $ids = Request :: get(self :: PARAM_COURSE_SECTION_ID);
        $failures = 0;

        if (! empty($ids))
        {
            if (! is_array($ids))
            {
                $ids = array($ids);
            }

            foreach ($ids as $id)
            {
                $course_section = \Ehb\Application\Avilarts\Storage\DataManager :: retrieve_by_id(
                    CourseSection :: class_name(),
                    $id);

                if (! $course_section->delete())
                {
                    $failures ++;
                }
            }

            if ($failures)
            {
                if (count($ids) == 1)
                {
                    $message = 'SelectedCourseSectionNotDeleted';
                }
                else
                {
                    $message = 'SelectedCourseSectionsNotDeleted';
                }
            }
            else
            {
                if (count($ids) == 1)
                {
                    $message = 'SelectedCourseSectionDeleted';
                }
                else
                {
                    $message = 'SelectedCourseSectionsDeleted';
                }
            }

            $this->redirect(
                Translation :: get($message),
                ($failures != 0 ? true : false),
                array(self :: PARAM_ACTION => self :: ACTION_VIEW_COURSE_SECTIONS));
        }
        else
        {
            return $this->display_error_page(htmlentities(Translation :: get('NoCourseSectionsSelected')));
        }
    }
}
