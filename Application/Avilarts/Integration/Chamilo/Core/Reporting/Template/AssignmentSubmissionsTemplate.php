<?php
namespace Ehb\Application\Avilarts\Integration\Chamilo\Core\Reporting\Template;

use Chamilo\Core\Reporting\ReportingTemplate;
use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Platform\Translation;
use Ehb\Application\Avilarts\Integration\Chamilo\Core\Reporting\Block\Assignment\AssignmentInformationBlock;
use Ehb\Application\Avilarts\Integration\Chamilo\Core\Reporting\Block\Assignment\AssignmentSubmissionsBlock;
use Ehb\Application\Avilarts\Storage\DataClass\ContentObjectPublication;

/**
 * Description of assignment_submissions_reporting_template
 * 
 * @author Anthony Hurst (Hogeschool Gent)
 */
class AssignmentSubmissionsTemplate extends ReportingTemplate
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
        $this->add_reporting_block(AssignmentSubmissionsBlock :: get_instance($this));
        
        $custom_breadcrumbs = array();
        
        $parameters = array();
        $parameters[\Ehb\Application\Avilarts\Manager :: PARAM_TOOL] = \Ehb\Application\Avilarts\Manager :: ACTION_REPORTING;
        $parameters[\Ehb\Application\Avilarts\Tool\Manager :: PARAM_PUBLICATION_ID] = $this->publication_id;
        $parameters[\Ehb\Application\Avilarts\Manager :: PARAM_TOOL_ACTION] = \Ehb\Application\Avilarts\Tool\Implementation\Reporting\Manager :: ACTION_VIEW;
        $custom_breadcrumbs[] = new Breadcrumb($this->get_url($parameters), $assignment->get_title());
        $custom_breadcrumbs[] = new Breadcrumb($this->get_url(), Translation :: get('SubmissionsOverview'));
        $this->set_custom_breadcrumb_trail($custom_breadcrumbs);
    }

    public function get_additional_parameters()
    {
        return array(
            \Ehb\Application\Avilarts\Tool\Manager :: PARAM_PUBLICATION_ID, 
            \Ehb\Application\Avilarts\Tool\Implementation\Assignment\Manager :: PARAM_TARGET_ID, 
            \Ehb\Application\Avilarts\Tool\Implementation\Assignment\Manager :: PARAM_SUBMITTER_TYPE);
    }

    private function init_parameters()
    {
        if ($this->publication_id)
        {
            $this->set_parameter(\Ehb\Application\Avilarts\Manager :: PARAM_PUBLICATION, $this->publication_id);
        }
    }
}
