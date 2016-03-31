<?php
namespace Ehb\Application\Avilarts\Integration\Chamilo\Core\Reporting\Template;

use Chamilo\Core\Reporting\ReportingTemplate;
use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Libraries\Platform\Session\Request;
use Ehb\Application\Avilarts\Integration\Chamilo\Core\Reporting\Block\Assignment\AssignmentCourseGroupsBlock;
use Ehb\Application\Avilarts\Integration\Chamilo\Core\Reporting\Block\Assignment\AssignmentInformationBlock;
use Ehb\Application\Avilarts\Integration\Chamilo\Core\Reporting\Block\Assignment\AssignmentPlatformGroupsBlock;
use Ehb\Application\Avilarts\Integration\Chamilo\Core\Reporting\Block\Assignment\AssignmentUsersBlock;
use Ehb\Application\Avilarts\Storage\DataClass\ContentObjectPublication;

/**
 *
 * @package application.weblcms.php.reporting.templates Reporting template with information about the assignment and the
 *          users, course groups and platform groups the assignment is published for
 * @author Joris Willems <joris.willems@gmail.com>
 * @author Alexander Van Paemel
 * @author Anthony Hurst (Hogeschool Gent)
 */
class CourseAssignmentSubmittersTemplate extends ReportingTemplate
{

    public function __construct($parent)
    {
        parent :: __construct($parent);
        
        $this->publication_id = Request :: get(\Ehb\Application\Avilarts\Manager :: PARAM_PUBLICATION);
        
        $assignment = \Ehb\Application\Avilarts\Storage\DataManager :: retrieve_by_id(
            ContentObjectPublication :: class_name(), 
            $this->publication_id)->get_content_object();
        
        $this->init_parameters();
        $this->add_reporting_block(new AssignmentInformationBlock($this));
        
        if (! $assignment->get_allow_group_submissions())
        {
            $this->add_reporting_block(new AssignmentUsersBlock($this));
        }
        else
        {
            $this->add_reporting_block(new AssignmentCourseGroupsBlock($this));
            $this->add_reporting_block(new AssignmentPlatformGroupsBlock($this));
        }
        
        $custom_breadcrumbs = array();
        $custom_breadcrumbs[] = new Breadcrumb($this->get_url(), $assignment->get_title());
        $this->set_custom_breadcrumb_trail($custom_breadcrumbs);
    }

    private function init_parameters()
    {
        if ($this->publication_id)
        {
            $this->set_parameter(\Ehb\Application\Avilarts\Manager :: PARAM_PUBLICATION, $this->publication_id);
        }
    }
}
