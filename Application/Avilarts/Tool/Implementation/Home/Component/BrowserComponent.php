<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\Home\Component;

use Ehb\Application\Avilarts\CourseSettingsController;
use Ehb\Application\Avilarts\Course\Storage\DataClass\CourseUserRelation;
use Ehb\Application\Avilarts\Renderer\ToolList\ToolListRenderer;
use Ehb\Application\Avilarts\Tool\Implementation\Home\Manager;
use Chamilo\Libraries\Format\Structure\BreadcrumbTrail;
use Chamilo\Libraries\Storage\Query\Condition\AndCondition;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;

class BrowserComponent extends Manager
{

    public function run()
    {
        $tools = $this->get_visible_tools();

        $intro_text_allowed = CourseSettingsController :: get_instance()->get_course_setting(
            $this->get_course_id(),
            \Ehb\Application\Avilarts\CourseSettingsConnector :: ALLOW_INTRODUCTION_TEXT);

        $html = array();

        if ($intro_text_allowed)
        {
            $html[] = $this->render_header($tools, true);
            $html[] = $this->display_introduction_text($this->get_introduction_text());
        }
        else
        {
            $html[] = $this->render_header($tools);
        }

        $conditions = array();
        $conditions[] = new EqualityCondition(
            new PropertyConditionVariable(CourseUserRelation :: class_name(), CourseUserRelation :: PROPERTY_STATUS),
            new StaticConditionVariable(CourseUserRelation :: STATUS_STUDENT));
        $conditions[] = new EqualityCondition(
            new PropertyConditionVariable(CourseUserRelation :: class_name(), CourseUserRelation :: PROPERTY_COURSE_ID),
            new StaticConditionVariable($this->get_course()->get_id()));
        $conditions[] = new EqualityCondition(
            new PropertyConditionVariable(CourseUserRelation :: class_name(), CourseUserRelation :: PROPERTY_USER_ID),
            new StaticConditionVariable($this->get_user_id()));
        $condition = new AndCondition($conditions);

        $renderer = ToolListRenderer :: factory(ToolListRenderer :: TYPE_FIXED, $this, $tools);
        $html[] = $renderer->toHtml();
        $html[] = '</div>';
        $html[] = $this->render_footer();

        return implode(PHP_EOL, $html);
    }

    public function add_additional_breadcrumbs(BreadcrumbTrail $breadcrumbtrail)
    {
        $breadcrumbtrail->add_help('weblcms_home_browser');
    }

    public function get_additional_parameters()
    {
        return array(\Ehb\Application\Avilarts\Tool\Manager :: PARAM_PUBLICATION_ID);
    }
}
