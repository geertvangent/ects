<?php
namespace Ehb\Application\Avilarts\Tool\Implementation\Search\Component;

use Ehb\Application\Avilarts\Storage\DataClass\ContentObjectPublication;
use Ehb\Application\Avilarts\Tool\Implementation\Search\Manager;
use Chamilo\Core\Repository\ContentObject\Introduction\Storage\DataClass\Introduction;
use Chamilo\Core\Repository\Storage\DataClass\ContentObject;
use Chamilo\Libraries\Format\Structure\ActionBarRenderer;
use Chamilo\Libraries\Platform\Session\Request;
use Chamilo\Libraries\Platform\Translation;
use Chamilo\Libraries\Storage\Query\Condition\AndCondition;
use Chamilo\Libraries\Storage\Query\Condition\EqualityCondition;
use Chamilo\Libraries\Storage\Query\Condition\InequalityCondition;
use Chamilo\Libraries\Storage\Query\Condition\NotCondition;
use Chamilo\Libraries\Storage\Query\Condition\OrCondition;
use Chamilo\Libraries\Storage\Query\Condition\PatternMatchCondition;
use Chamilo\Libraries\Storage\Query\Variable\PropertyConditionVariable;
use Chamilo\Libraries\Storage\Query\Variable\StaticConditionVariable;
use Chamilo\Libraries\Utilities\String\Text;
use Chamilo\Libraries\Storage\Parameters\DataClassRetrievesParameters;

/**
 * $Id: search_searcher.class.php 216 2009-11-13 14:08:06Z kariboe $
 *
 * @package application.lib.weblcms.tool.search.component
 */
/**
 * Tool to search in the course.
 *
 * @todo : Link from search results to location in course
 * @todo : Advanced search (only in recent publications, only certain types of publications, only in a given tool,...)
 */
class SearcherComponent extends Manager
{
    /**
     * Number of results per page
     */
    const RESULTS_PER_PAGE = 10;

    private $action_bar;

    // Inherited
    public function run()
    {
        $this->action_bar = $this->get_action_bar();

        $html = array();

        $html[] = $this->render_header();

        $html[] = '<div style="text-align:center">';
        $html[] = $this->action_bar->as_html();
        $html[] = '</div>';

        // If form validates, show results
        $query = $this->get_query();
        if ($query)
        {
            $course_groups = $this->get_course_groups();

            $course_group_ids = array();

            foreach ($course_groups as $course_group)
            {
                $course_group_ids[] = $course_group->get_id();
            }

            $publications = \Ehb\Application\Avilarts\Storage\DataManager :: retrieves(
                ContentObjectPublication :: class_name(),
                new DataClassRetrievesParameters($this->get_retrieve_publications_condition()));

            $tools = array();

            while ($publication = $publications->next_result())
            {
                if ($this->is_allowed(\Ehb\Application\Avilarts\Rights\Rights :: VIEW_RIGHT, $publication) && (! $publication->is_hidden() ||
                     $this->is_allowed(\Ehb\Application\Avilarts\Rights\Rights :: EDIT_RIGHT)))
                {
                    $tools[$publication->get_tool()][] = $publication;
                }
            }

            $resultsHtml = array();
            $results = 0;

            foreach ($tools as $tool => $publications)
            {
                if (strpos($tool, 'feedback') !== false)
                {
                    continue;
                }

                $objects = array();

                foreach ($publications as $publication)
                {
                    $lo = $publication->get_content_object();
                    $lo_title = $lo->get_title();
                    $lo_description = strip_tags($lo->get_description());

                    if (stripos($lo_title, $query) !== false || stripos($lo_description, $query) !== false)
                    {
                        $objects[] = $publication;
                    }
                }

                $count = count($objects);

                if ($count > 0)
                {
                    $resultsHtml[] = '<h4>' .
                         Translation :: get(
                            'TypeName',
                            null,
                            \Ehb\Application\Avilarts\Tool\Manager :: get_tool_type_namespace($tool)) . ' (' . Translation :: get(
                            $count == 1 ? 'SearchResult' : 'SearchResults',
                            array('COUNT' => $count)) . ') </h4>';
                    $results += $count;

                    foreach ($objects as $pub)
                    {
                        $object = $pub->get_content_object();

                        if ($object->get_type() != Introduction :: class_name())
                        {
                            $url = $this->get_url(
                                array(
                                    \Ehb\Application\Avilarts\Manager :: PARAM_TOOL => $tool,
                                    \Ehb\Application\Avilarts\Tool\Manager :: PARAM_PUBLICATION_ID => $pub->get_id(),
                                    \Ehb\Application\Avilarts\Tool\Manager :: PARAM_ACTION => \Ehb\Application\Avilarts\Tool\Manager :: ACTION_VIEW));
                        }
                        else
                        {
                            $url = '#';
                        }

                        $resultsHtml[] = '<div class="content_object" style="background-image: url(' .
                             $object->get_icon_path() . ');">';
                        $resultsHtml[] = '<div class="title"><a href="' . $url . '">' .
                             Text :: highlight($object->get_title(), $query, 'yellow') . '</a></div>';
                        $resultsHtml[] = '<div class="description">' .
                             Text :: highlight(strip_tags($object->get_description()), $query, 'yellow') . '</div>';
                        $resultsHtml[] = '</div>';
                    }
                }
            }

            $html[] = $results . ' ' . Translation :: get('ResultsFoundFor') .
                 ' <span style="background-color: yellow;">' . $query . '</span>';
            $html[] = implode(PHP_EOL, $resultsHtml);
        }

        $html[] = $this->render_footer();

        return implode(PHP_EOL, $html);
    }

    public function get_action_bar()
    {
        $action_bar = new ActionBarRenderer(ActionBarRenderer :: TYPE_HORIZONTAL);

        $action_bar->set_search_url(
            $this->get_url(array(\Ehb\Application\Avilarts\Tool\Manager :: PARAM_ACTION => self :: ACTION_SEARCH)));

        return $action_bar;
    }

    public function get_condition()
    {
        $query = $this->get_query();
        if (! $query)
        {
            $query = Request :: post('query');
        }

        if (isset($query) && $query != '')
        {
            $conditions[] = new PatternMatchCondition(
                new PropertyConditionVariable(ContentObject :: class_name(), ContentObject :: PROPERTY_TITLE),
                '*' . $query . '*');
            $conditions[] = new PatternMatchCondition(
                new PropertyConditionVariable(ContentObject :: class_name(), ContentObject :: PROPERTY_DESCRIPTION),
                '*' . $query . '*');
            return new OrCondition($conditions);
        }

        return null;
    }

    public function get_query()
    {
        $query = trim($this->action_bar->get_query());
        if ($query == '')
        {
            return null;
        }

        return $query;
    }

    /**
     *
     * @return AndCondition
     */
    protected function get_retrieve_publications_condition()
    {
        $conditions = array();

        $conditions[] = new EqualityCondition(
            new PropertyConditionVariable(
                ContentObjectPublication :: class_name(),
                ContentObjectPublication :: PROPERTY_COURSE_ID),
            new StaticConditionVariable($this->get_course_id()));
        $conditions[] = new NotCondition(
            new EqualityCondition(
                new PropertyConditionVariable(
                    ContentObjectPublication :: class_name(),
                    ContentObjectPublication :: PROPERTY_TOOL),
                new StaticConditionVariable('home')));

        if (! $this->get_course()->is_course_admin($this->get_user()))
        {
            $from_date_variables = new PropertyConditionVariable(
                ContentObjectPublication :: class_name(),
                ContentObjectPublication :: PROPERTY_FROM_DATE);

            $to_date_variable = new PropertyConditionVariable(
                ContentObjectPublication :: class_name(),
                ContentObjectPublication :: PROPERTY_TO_DATE);

            $time_conditions = array();

            $time_conditions[] = new EqualityCondition(
                new PropertyConditionVariable(
                    ContentObjectPublication :: class_name(),
                    ContentObjectPublication :: PROPERTY_HIDDEN),
                new StaticConditionVariable(0));

            $forever_conditions = array();

            $forever_conditions[] = new EqualityCondition($from_date_variables, new StaticConditionVariable(0));

            $forever_conditions[] = new EqualityCondition($to_date_variable, new StaticConditionVariable(0));

            $forever_condition = new AndCondition($forever_conditions);

            $between_conditions = array();

            $between_conditions[] = new InequalityCondition(
                $from_date_variables,
                InequalityCondition :: LESS_THAN_OR_EQUAL,
                new StaticConditionVariable(time()));

            $between_conditions[] = new InequalityCondition(
                $to_date_variable,
                InequalityCondition :: GREATER_THAN_OR_EQUAL,
                new StaticConditionVariable(time()));

            $between_condition = new AndCondition($between_conditions);

            $time_conditions[] = new OrCondition(array($forever_condition, $between_condition));

            $conditions[] = new AndCondition($time_conditions);
        }

        $condition = new AndCondition($conditions);

        return $condition;
    }
}
