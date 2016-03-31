<?php
namespace Ehb\Application\Avilarts\Integration\Chamilo\Core\Reporting\Template;

use Chamilo\Core\Reporting\ReportingTemplate;
use Ehb\Application\Avilarts\Integration\Chamilo\Core\Reporting\Block\Admin\CoursesPerCategoryBlock;
use Ehb\Application\Avilarts\Integration\Chamilo\Core\Reporting\Block\Admin\MostActiveInactiveLastDetailBlock;
use Ehb\Application\Avilarts\Integration\Chamilo\Core\Reporting\Block\Admin\MostActiveInactiveLastPublicationBlock;
use Ehb\Application\Avilarts\Integration\Chamilo\Core\Reporting\Block\Admin\MostActiveInactiveLastVisitBlock;
use Ehb\Application\Avilarts\Integration\Chamilo\Core\Reporting\Block\Admin\NoOfCoursesBlock;
use Ehb\Application\Avilarts\Integration\Chamilo\Core\Reporting\Block\Admin\NoOfCoursesByLanguageBlock;
use Ehb\Application\Avilarts\Integration\Chamilo\Core\Reporting\Block\Admin\NoOfUsersSubscribedCourseBlock;
use Ehb\Application\Avilarts\Integration\Chamilo\Core\Reporting\Block\Tool\LastAccessToToolsPlatformBlock;

/**
 * $Id: course_data_reporting_template.class.php 216 2009-11-13 14:08:06Z kariboe $
 * 
 * @package application.lib.weblcms.reporting.templates
 */
class CourseDataTemplate extends ReportingTemplate
{

    public function __construct($parent)
    {
        parent :: __construct($parent);
        $this->add_reporting_block(new NoOfCoursesBlock($this));
        $this->add_reporting_block(new NoOfCoursesByLanguageBlock($this));
        $this->add_reporting_block(new MostActiveInactiveLastVisitBlock($this));
        $this->add_reporting_block(new MostActiveInactiveLastPublicationBlock($this));
        $this->add_reporting_block(new MostActiveInactiveLastDetailBlock($this));
        // $this->add_reporting_block(new NoOfObjectsPerTypeBlock($this));
        // $this->add_reporting_block(new NoOfPublishedObjectsPerTypeBlock($this));
        $this->add_reporting_block(new CoursesPerCategoryBlock($this));
        $this->add_reporting_block(new LastAccessToToolsPlatformBlock($this));
        $this->add_reporting_block(new NoOfUsersSubscribedCourseBlock($this));
    }
}
