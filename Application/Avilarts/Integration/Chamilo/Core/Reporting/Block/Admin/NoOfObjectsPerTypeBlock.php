<?php
namespace Ehb\Application\Avilarts\Integration\Chamilo\Core\Reporting\Block\Admin;

use Chamilo\Core\Reporting\ReportingData;
use Chamilo\Core\Reporting\Viewer\Rendition\Block\Type\Html;
use Ehb\Application\Avilarts\Integration\Chamilo\Core\Reporting\Block\CourseBlock;

class NoOfObjectsPerTypeBlock extends CourseBlock
{

    public function count_data()
    {
        $reporting_data = new ReportingData();
        return $reporting_data;
    }

    public function retrieve_data()
    {
        return $this->count_data();
    }

    public function get_views()
    {
        return array(Html :: VIEW_TABLE, Html :: VIEW_BAR, Html :: VIEW_LINE, Html :: VIEW_STACKED_AREA);
    }
}
