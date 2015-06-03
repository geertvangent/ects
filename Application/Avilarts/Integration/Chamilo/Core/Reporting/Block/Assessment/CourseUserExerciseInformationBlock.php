<?php
namespace Ehb\Application\Avilarts\Integration\Chamilo\Core\Reporting\Block\Assessment;

use Ehb\Application\Avilarts\Integration\Chamilo\Core\Reporting\Block\ToolBlock;
use Ehb\Application\Avilarts\Integration\Chamilo\Core\Reporting\Template\AssessmentAttemptsUserTemplate;
use Ehb\Application\Avilarts\Integration\Chamilo\Core\Tracking\Storage\DataClass\AssessmentAttempt;
use Ehb\Application\Avilarts\Storage\DataClass\ContentObjectPublication;
use Chamilo\Core\Reporting\ReportingData;
use Chamilo\Core\Repository\ContentObject\Assessment\Storage\DataClass\Assessment;
use Chamilo\Libraries\Architecture\Application\Application;
use Chamilo\Libraries\Architecture\ClassnameUtilities;
use Chamilo\Libraries\File\Redirect;
use Chamilo\Libraries\Format\Theme;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;
use Chamilo\Libraries\Storage\Query\Condition\AndCondition;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Chamilo\Libraries\Utilities\DatetimeUtilities;
use Chamilo\Libraries\Utilities\Utilities;
use Chamilo\Core\Repository\Storage\DataClass\ContentObject;

/**
 *
 * @package application.weblcms.php.reporting.blocks Reporting block with an overiew of the assessments the user has
 *          attempted
 * @author Joris Willems <joris.willems@gmail.com>
 * @author Alexander Van Paemel
 */
class CourseUserExerciseInformationBlock extends ToolBlock
{

    public function count_data()
    {
        $reporting_data = new ReportingData();
        $reporting_data->set_rows(
            array(
                Translation :: get('Title'),
                Translation :: get('NumberOfAttempts'),
                Translation :: get('LastAttempt'),
                Translation :: get('TotalTime'),
                Translation :: get('AverageScore'),
                Translation :: get('Attempts')));
        $course_id = $this->get_course_id();
        $user_id = $this->get_user_id();

        $img = '<img src="' . Theme :: getInstance()->getCommonImagePath('Action/Reporting') . '" title="' .
             Translation :: get('Details') . '" />';

        $conditions[] = new EqualityCondition(
            new PropertyConditionVariable(AssessmentAttempt :: class_name(), AssessmentAttempt :: PROPERTY_COURSE_ID),
            new StaticConditionVariable($course_id));
        $conditions[] = new EqualityCondition(
            new PropertyConditionVariable(AssessmentAttempt :: class_name(), AssessmentAttempt :: PROPERTY_USER_ID),
            new StaticConditionVariable($user_id));
        $condition = new AndCondition($conditions);

        $trackerdata = \Chamilo\Libraries\Storage\DataManager\DataManager :: retrieves(
            AssessmentAttempt :: class_name(),
            new DataClassRetrievesParameters($condition));

        $exercises = array();
        // $score_count = 0;
        while ($value = $trackerdata->next_result())
        {
            if ($value->get_status() == AssessmentAttempt :: STATUS_COMPLETED)
            {
                $exercises[$value->get_assessment_id()]['score'] += $value->get_total_score();
                $exercises[$value->get_assessment_id()]['score_count'] ++;
            }
            $exercises[$value->get_assessment_id()]['count'] ++;
            $exercises[$value->get_assessment_id()]['total_time'] += $value->get_total_time();

            if ($exercises[$value->get_assessment_id()]['last'] == null ||
                 $value->get_start_time() > $exercises[$value->get_assessment_id()]['last'])
            {
                $exercises[$value->get_assessment_id()]['last'] = $value->get_start_time();
            }
        }

        $params = array();
        $params[Application :: PARAM_ACTION] = \Ehb\Application\Avilarts\Manager :: ACTION_VIEW_COURSE;
        $params[Application :: PARAM_CONTEXT] = \Ehb\Application\Avilarts\Manager :: context();
        $params[\Ehb\Application\Avilarts\Manager :: PARAM_COURSE] = $course_id;
        $params[\Ehb\Application\Avilarts\Manager :: PARAM_TOOL] = Assessment :: get_type_name();
        $params[\Ehb\Application\Avilarts\Manager :: PARAM_TOOL_ACTION] = \Ehb\Application\Avilarts\Tool\Manager :: ACTION_VIEW;

        $params_detail = $this->get_parent()->get_parameters();
        $params_detail[\Ehb\Application\Avilarts\Manager :: PARAM_TEMPLATE_ID] = AssessmentAttemptsUserTemplate :: class_name();

        $conditions = array();
        $conditions[] = new EqualityCondition(
            new PropertyConditionVariable(
                ContentObjectPublication :: class_name(),
                ContentObjectPublication :: PROPERTY_TOOL),
            new StaticConditionVariable(
                ClassnameUtilities :: getInstance()->getClassNameFromNamespace(Assessment :: class_name(), true)));
        $conditions[] = new EqualityCondition(
            new PropertyConditionVariable(
                ContentObjectPublication :: class_name(),
                ContentObjectPublication :: PROPERTY_COURSE_ID),
            new StaticConditionVariable($course_id));
        $condition = new AndCondition($conditions);
        $publications_resultset = \Ehb\Application\Avilarts\Storage\DataManager :: retrieve_content_object_publications(
            $condition);

        $key = 0;
        while ($publication = $publications_resultset->next_result())
        {
            if (! \Ehb\Application\Avilarts\Storage\DataManager :: is_publication_target_user(
                $user_id,
                $publication[ContentObjectPublication :: PROPERTY_ID]))
            {
                continue;
            }
            ++ $key;
            $title = $score = $last = $last = $link = $time = null;
            $count = 0;

            if ($exercises[$publication[ContentObjectPublication :: PROPERTY_ID]])
            {
                $value = $exercises[$publication[ContentObjectPublication :: PROPERTY_ID]];
                $time = mktime(0, 0, $value[total_time], 0, 0, 0);
                $time = date('G:i:s', $time);
                $score = $this->get_score_bar($value['score'] / $value['score_count']);
                $last = DatetimeUtilities :: format_locale_date(
                    Translation :: get('DateFormatShort', null, Utilities :: COMMON_LIBRARIES) . ', ' .
                         Translation :: get('TimeNoSecFormat', null, Utilities :: COMMON_LIBRARIES),
                        $value['last']);
                $params_detail[\Ehb\Application\Avilarts\Manager :: PARAM_PUBLICATION] = $publication[ContentObjectPublication :: PROPERTY_ID];
                $link = '<a href="' . $this->get_parent()->get_url($params_detail) . '">' . $img . '</a>';
                $count = $value['count'];
            }

            $params[\Ehb\Application\Avilarts\Manager :: PARAM_PUBLICATION] = $publication[ContentObjectPublication :: PROPERTY_ID];

            $content_object = \Chamilo\Core\Repository\Storage\DataManager :: retrieve_by_id(
                ContentObject :: class_name(),
                $publication[ContentObjectPublication :: PROPERTY_CONTENT_OBJECT_ID]);

            $redirect = new Redirect($params);
            $link = $redirect->getUrl();

            $title = '<a href="' . $link . '">' . $content_object->get_title() . '</a>';

            $reporting_data->add_category($key);
            $reporting_data->add_data_category_row($key, Translation :: get('Title'), $title);
            $reporting_data->add_data_category_row($key, Translation :: get('NumberOfAttempts'), $count);
            $reporting_data->add_data_category_row($key, Translation :: get('AverageScore'), $score);
            $reporting_data->add_data_category_row($key, Translation :: get('LastAttempt'), $last);
            $reporting_data->add_data_category_row($key, Translation :: get('TotalTime'), $time);
            $reporting_data->add_data_category_row($key, Translation :: get('Attempts'), $link);
        }
        $reporting_data->hide_categories();
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
