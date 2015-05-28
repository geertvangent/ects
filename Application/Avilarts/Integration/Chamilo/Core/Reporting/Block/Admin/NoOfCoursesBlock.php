<?php
namespace Ehb\Application\Avilarts\Integration\Chamilo\Core\Reporting\Block\Admin;

use Ehb\Application\Avilarts\Course\Storage\DataClass\Course;
use Ehb\Application\Avilarts\Course\Storage\DataManager as CourseDataManager;
use Ehb\Application\Avilarts\Integration\Chamilo\Core\Reporting\Block\CourseBlock;
use Chamilo\Core\Reporting\ReportingData;
use Chamilo\Libraries\Platform\Translation;

class NoOfCoursesBlock extends CourseBlock
{

    public function count_data()
    {
        $reporting_data = new ReportingData();
        $count = CourseDataManager :: count(Course :: class_name());
        
        $reporting_data->set_categories(array(Translation :: get('CourseCount')));
        $reporting_data->set_rows(array(Translation :: get('count')));
        
        $reporting_data->add_data_category_row(Translation :: get('CourseCount'), Translation :: get('count'), $count);
        
        return $reporting_data;
    }

    public function retrieve_data()
    {
        return $this->count_data();
    }

    public function get_views()
    {
        return array(\Chamilo\Core\Reporting\Viewer\Rendition\Block\Type\Html :: VIEW_TABLE);
    }
}
