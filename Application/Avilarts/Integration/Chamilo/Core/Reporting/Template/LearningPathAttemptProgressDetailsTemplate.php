<?php
namespace Ehb\Application\Avilarts\Integration\Chamilo\Core\Reporting\Template;

use Ehb\Application\Avilarts\Integration\Chamilo\Core\Reporting\Block\LearningPath\LearningPathAttemptProgressDetailsBlock;
use Chamilo\Core\Reporting\ReportingTemplate;
use Chamilo\Libraries\Platform\Session\Request;

/**
 * $Id: learning_path_attempt_progress_reporting_template.class.php 216 2009-11-13 14:08:06Z kariboe $
 * 
 * @package application.lib.weblcms.reporting.templates
 */
/**
 *
 * @author Michael Kyndt
 */
class LearningPathAttemptProgressDetailsTemplate extends ReportingTemplate
{

    public function __construct($parent)
    {
        parent :: __construct($parent);
        $this->initialize_parameters();
        $this->add_reporting_block($this->get_learning_path_progress_details());
    }

    private function initialize_parameters()
    {
        $course_id = Request :: get(\Ehb\Application\Avilarts\Manager :: PARAM_COURSE);
        if ($course_id)
        {
            $this->set_parameter(\Ehb\Application\Avilarts\Manager :: PARAM_COURSE, $course_id);
        }
        
        $tool = Request :: get(\Ehb\Application\Avilarts\Manager :: PARAM_TOOL);
        $this->set_parameter(\Ehb\Application\Avilarts\Manager :: PARAM_TOOL, $tool);
        
        $attempt_id = Request :: get(
            \Ehb\Application\Avilarts\Tool\Implementation\LearningPath\Manager :: PARAM_ATTEMPT_ID);
        if ($attempt_id)
        {
            $this->set_parameter(
                \Ehb\Application\Avilarts\Tool\Implementation\LearningPath\Manager :: PARAM_ATTEMPT_ID, 
                $attempt_id);
        }
        
        if ($this->get_parent()->get_parameter(\Ehb\Application\Avilarts\Tool\Manager :: PARAM_ACTION) ==
             \Ehb\Application\Avilarts\Tool\Manager :: ACTION_VIEW)
        {
            $this->set_parameter('lp_action', 'view_progress');
        }
        
        $pid = Request :: get(\Ehb\Application\Avilarts\Tool\Manager :: PARAM_PUBLICATION_ID);
        $this->set_parameter(\Ehb\Application\Avilarts\Tool\Manager :: PARAM_PUBLICATION_ID, $pid);
        
        $parent_id = Request :: get(\Ehb\Application\Avilarts\Tool\Manager :: PARAM_COMPLEX_ID);
        $this->set_parameter(\Ehb\Application\Avilarts\Tool\Manager :: PARAM_COMPLEX_ID, $parent_id);
    }

    public function get_learning_path_progress_details()
    {
        $course_weblcms_block = new LearningPathAttemptProgressDetailsBlock($this);
        return $course_weblcms_block;
    }
}
