<?php
namespace Ehb\Application\Avilarts\Integration\Chamilo\Core\Reporting\Block\Publication;

use Ehb\Application\Avilarts\Integration\Chamilo\Core\Reporting\Block\ToolBlock;
use Ehb\Application\Avilarts\Integration\Chamilo\Core\Tracking\Storage\DataClass\CourseVisit;
use Ehb\Application\Avilarts\Integration\Chamilo\Core\Tracking\Storage\DataManager as WeblcmsTrackingDataManager;
use Ehb\Application\Avilarts\Storage\DataClass\ContentObjectPublication;
use Chamilo\Core\Reporting\ReportingData;
use Chamilo\Core\User\Storage\DataClass\User;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;

class PublicationAccessBlock extends ToolBlock
{

    public function count_data()
    {
        $reporting_data = new ReportingData();

        $reporting_data->set_rows(
            array(
                Translation :: get('User', null, \Chamilo\Core\User\Manager :: context()),
                Translation :: get('OfficialCode', null, \Chamilo\Core\User\Manager :: context())));

        $this->add_reporting_data_rows_for_course_visit_data($reporting_data);

        $course_visits = $this->retrieve_course_visits();

        for ($counter = 0; $counter < $course_visits->size(); $counter ++)
        {
            $reporting_data->add_category($counter);
        }

        $counter = 0;

        while ($course_visit = $course_visits->next_result())
        {
            $user = \Chamilo\Core\User\Storage\DataManager :: retrieve_by_id(
                User :: class_name(),
                $course_visit->get_user_id());

            $reporting_data->add_data_category_row(
                $counter,
                Translation :: get('User', null, \Chamilo\Core\User\Manager :: context()),
                $user->get_fullname());

            $reporting_data->add_data_category_row(
                $counter,
                Translation :: get('OfficialCode', null, \Chamilo\Core\User\Manager :: context()),
                $user->get_official_code());

            $this->add_reporting_data_from_course_visit_as_row($counter, $reporting_data, $course_visit);

            $counter ++;
        }

        $reporting_data->hide_categories();
        return $reporting_data;
    }

    public function retrieve_data()
    {
        return $this->count_data();
    }

    /**
     * Retrieves the course visit records for the given publication
     *
     * @return \libraries\storage\ResultSet
     */
    public function retrieve_course_visits()
    {
        $content_object_publication_id = $this->get_publication_id();
        $content_object_publication = \Ehb\Application\Avilarts\Storage\DataManager :: retrieve_by_id(
            ContentObjectPublication :: class_name(),
            $content_object_publication_id);

        $category_id = $content_object_publication->get_category_id();
        $category_id = $category_id ? $category_id : null;

        $tool_id = $this->get_tool_registration($content_object_publication->get_tool())->get_id();

        $condition = WeblcmsTrackingDataManager :: get_course_visit_conditions_by_course_data(
            $content_object_publication->get_course_id(),
            $tool_id,
            $category_id,
            $content_object_publication_id,
            false);

        return WeblcmsTrackingDataManager :: retrieves(
            CourseVisit :: class_name(),
            new DataClassRetrievesParameters($condition));
    }

    public function get_views()
    {
        return array(\Chamilo\Core\Reporting\Viewer\Rendition\Block\Type\Html :: VIEW_TABLE);
    }
}
