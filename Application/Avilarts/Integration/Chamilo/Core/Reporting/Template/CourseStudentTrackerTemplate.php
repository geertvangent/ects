<?php
namespace Ehb\Application\Avilarts\Integration\Chamilo\Core\Reporting\Template;

use Chamilo\Core\Reporting\ReportingTemplate;
use Chamilo\Libraries\Platform\Session\Request;
use Ehb\Application\Avilarts\Integration\Chamilo\Core\Reporting\Block\Assessment\AssessmentsBlock;
use Ehb\Application\Avilarts\Integration\Chamilo\Core\Reporting\Block\Assignment\AssignmentBlock;
use Ehb\Application\Avilarts\Integration\Chamilo\Core\Reporting\Block\LearningPath\LearningPathBlock;
use Ehb\Application\Avilarts\Integration\Chamilo\Core\Reporting\Block\Tool\LastAccessToToolsBlock;
use Ehb\Application\Avilarts\Integration\Chamilo\Core\Reporting\Block\User\UsersTrackingBlock;

/**
 * $Id: course_student_tracker_reporting_template.class.php 216 2009-11-13 14:08:06Z kariboe $
 * 
 * @package application.lib.weblcms.reporting.templates
 */
/**
 *
 * @author Michael Kyndt
 */
class CourseStudentTrackerTemplate extends ReportingTemplate
{

    public function __construct($parent)
    {
        parent :: __construct($parent);
        
        $this->init_parameters();
        $this->add_reporting_block(new UsersTrackingBlock($this));
        $this->add_reporting_block(new AssignmentBlock($this));
        $this->add_reporting_block(new AssessmentsBlock($this));
        $this->add_reporting_block(new LearningPathBlock($this));
        $this->add_reporting_block(new LastAccessToToolsBlock($this));
    }

    private function init_parameters()
    {
        $course_id = Request :: get(\Ehb\Application\Avilarts\Manager :: PARAM_COURSE);
        if ($course_id)
        {
            $this->set_parameter(\Ehb\Application\Avilarts\Manager :: PARAM_COURSE, $course_id);
        }
    }
}
