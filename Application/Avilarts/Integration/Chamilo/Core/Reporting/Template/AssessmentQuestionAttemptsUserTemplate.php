<?php
namespace Ehb\Application\Avilarts\Integration\Chamilo\Core\Reporting\Template;

use Ehb\Application\Avilarts\Integration\Chamilo\Core\Reporting\Block\Assessment\AssessmentQuestionAttemptsUserBlock;
use Ehb\Application\Avilarts\Integration\Chamilo\Core\Reporting\Block\Assessment\AssessmentQuestionUserInformationBlock;
use Ehb\Application\Avilarts\Storage\DataClass\ContentObjectPublication;
use Chamilo\Core\Reporting\ReportingTemplate;
use Chamilo\Libraries\Format\Structure\Breadcrumb;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Core\Repository\Storage\DataClass\ComplexContentObjectItem;

/**
 *
 * @package application.weblcms.php.reporting.templates Reporting template with the assessment question attempts of one
 *          user
 * @author Joris Willems <joris.willems@gmail.com>
 * @author Alexander Van Paemel
 */
class AssessmentQuestionAttemptsUserTemplate extends ReportingTemplate
{

    public function __construct($parent)
    {
        parent :: __construct($parent);

        $this->set_parameter(
            \Ehb\Application\Avilarts\Tool\Implementation\Reporting\Manager :: PARAM_QUESTION,
            Request :: get(\Ehb\Application\Avilarts\Tool\Implementation\Reporting\Manager :: PARAM_QUESTION));
        $this->set_parameter(
            \Ehb\Application\Avilarts\Manager :: PARAM_PUBLICATION,
            Request :: get(\Ehb\Application\Avilarts\Manager :: PARAM_PUBLICATION));
        $this->set_parameter(
            \Ehb\Application\Avilarts\Manager :: PARAM_USERS,
            Request :: get(\Ehb\Application\Avilarts\Manager :: PARAM_USERS));

        $this->add_reporting_block(new AssessmentQuestionUserInformationBlock($this));

        $this->add_reporting_block(new AssessmentQuestionAttemptsUserBlock($this));
        $this->add_breadcrumbs();
    }

    /**
     * Adds the breadcrumbs to the breadcrumbtrail
     */
    protected function add_breadcrumbs()
    {
        $assessment = \Ehb\Application\Avilarts\Storage\DataManager :: retrieve_by_id(
            ContentObjectPublication :: class_name(),
            $this->get_parameter(\Ehb\Application\Avilarts\Manager :: PARAM_PUBLICATION))->get_content_object();

        $question = \Chamilo\Core\Repository\Storage\DataManager :: retrieve_by_id(
            ComplexContentObjectItem :: class_name(),
            $this->get_parameter(\Ehb\Application\Avilarts\Tool\Implementation\Reporting\Manager :: PARAM_QUESTION));

        $trail = BreadcrumbTrail :: get_instance();

        $trail->add(
            new Breadcrumb(
                $this->get_url(
                    array(\Chamilo\Core\Reporting\Viewer\Manager :: PARAM_BLOCK_ID => 2),
                    array(\Ehb\Application\Avilarts\Manager :: PARAM_TEMPLATE_ID)),
                Translation :: get('Assessments')));

        $filters = array(
            \Ehb\Application\Avilarts\Manager :: PARAM_USERS,
            \Ehb\Application\Avilarts\Tool\Implementation\Reporting\Manager :: PARAM_QUESTION);

        $params = array();
        $params[\Ehb\Application\Avilarts\Manager :: PARAM_TEMPLATE_ID] = AssessmentAttemptsTemplate :: class_name();
        $params[\Ehb\Application\Avilarts\Manager :: PARAM_PUBLICATION] = $this->publication_id;

        $trail->add(new Breadcrumb($this->get_url($params, $filters), $assessment->get_title()));

        $params[\Chamilo\Core\Reporting\Viewer\Manager :: PARAM_BLOCK_ID] = 2;

        $trail->add(new Breadcrumb($this->get_url($params, $filters), Translation :: get('Questions')));

        $filters = array(\Ehb\Application\Avilarts\Manager :: PARAM_USERS);

        $params = array();
        $params[\Ehb\Application\Avilarts\Manager :: PARAM_TEMPLATE_ID] = AssessmentQuestionUsersTemplate :: class_name();
        $params[\Ehb\Application\Avilarts\Manager :: PARAM_PUBLICATION] = $this->publication_id;

        $trail->add(new Breadcrumb($this->get_url($params, $filters), $question->get_ref_object()->get_title()));

        $params[\Chamilo\Core\Reporting\Viewer\Manager :: PARAM_BLOCK_ID] = 0;

        $trail->add(new Breadcrumb($this->get_url($params, $filters), Translation :: get('Users')));

        $trail->add(
            new Breadcrumb(
                $this->get_url(),
                \Chamilo\Core\User\Storage\DataManager :: get_fullname_from_user(
                    $this->get_parameter(\Ehb\Application\Avilarts\Manager :: PARAM_USERS))));
    }
}
