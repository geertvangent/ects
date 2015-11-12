<?php
namespace Ehb\Application\Avilarts\Integration\Chamilo\Core\Reporting\Block\Tool;

use Ehb\Application\Avilarts\Integration\Chamilo\Core\Tracking\Storage\DataManager as WeblcmsTrackingDataManager;

class LastAccessToToolsPlatformBlock extends ToolAccessBlock
{

    /**
     * Returns the summary data for this course
     * 
     * @return RecordResultSet
     */
    public function retrieve_course_summary_data()
    {
        return WeblcmsTrackingDataManager :: retrieve_tools_access_summary_data();
    }
}
