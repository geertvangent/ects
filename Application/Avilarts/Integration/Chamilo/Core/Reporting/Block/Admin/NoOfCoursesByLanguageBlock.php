<?php
namespace Ehb\Application\Avilarts\Integration\Chamilo\Core\Reporting\Block\Admin;

use Chamilo\Core\Reporting\ReportingData;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;
use Chamilo\Libraries\Utilities\Utilities;
use Ehb\Application\Avilarts\Course\Storage\DataClass\Course;
use Ehb\Application\Avilarts\Course\Storage\DataManager as CourseDataManager;
use Ehb\Application\Avilarts\CourseSettingsConnector;
use Ehb\Application\Avilarts\CourseSettingsController;
use Ehb\Application\Avilarts\Integration\Chamilo\Core\Reporting\Block\CourseBlock;

class NoOfCoursesByLanguageBlock extends CourseBlock
{

    public function count_data()
    {
        $reporting_data = new ReportingData();
        $arr = array();
        $courses = CourseDataManager :: retrieves(Course :: class_name(), new DataClassRetrievesParameters());

        $categories = array();

        while ($course = $courses->next_result())
        {
            $lang = CourseSettingsController :: get_instance()->get_course_setting(
                $course->get_id(),
                CourseSettingsConnector :: LANGUAGE);

            $categories[$lang] = Translation :: get($lang, null, Utilities :: COMMON_LIBRARIES);

            if ($arr[$lang])
            {
                $arr[$lang] = $arr[$lang] + 1;
            }
            else
            {

                $arr[$lang] = 1;
            }
        }
        $reporting_data->set_categories($categories);
        $reporting_data->set_rows(array(Translation :: get('count')));

        foreach ($categories as $key => $name)
        {
            $reporting_data->add_data_category_row($key, Translation :: get('count'), ($arr[$key]));
        }

        return $reporting_data;
    }

    public function retrieve_data()
    {
        return $this->count_data();
    }

    public function get_views()
    {
        return array(
            \Chamilo\Core\Reporting\Viewer\Rendition\Block\Type\Html :: VIEW_TABLE,
            \Chamilo\Core\Reporting\Viewer\Rendition\Block\Type\Html :: VIEW_PIE);
    }
}
